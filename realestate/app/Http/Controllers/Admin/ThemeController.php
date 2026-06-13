<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    /**
     * Display a listing of available themes.
     */
    public function index(SettingsService $settings): View
    {
        $themesDir = resource_path('views/themes');
        $themes = [];
        $activeTheme = $settings->get('active_theme', 'default');

        if (File::exists($themesDir)) {
            $directories = File::directories($themesDir);

            foreach ($directories as $dir) {
                $folder = basename($dir);
                $jsonPath = $dir . '/theme.json';

                $themeData = [
                    'folder' => $folder,
                    'name' => ucfirst($folder),
                    'description' => 'A custom frontend theme.',
                    'version' => '1.0.0',
                    'author' => 'Unknown',
                    'screenshot' => null,
                    'isActive' => $folder === $activeTheme
                ];

                if (File::exists($jsonPath)) {
                    $jsonContent = json_decode(File::get($jsonPath), true);
                    if (is_array($jsonContent)) {
                        $themeData = array_merge($themeData, $jsonContent);
                    }
                }

                $themes[] = $themeData;
            }
        }

        return view('admin.themes.index', compact('themes', 'activeTheme'));
    }

    /**
     * Activate the specified theme.
     */
    public function activate(Request $request, SettingsService $settings): RedirectResponse
    {
        $request->validate([
            'theme' => 'required|string'
        ]);

        $theme = $request->theme;
        $themePath = resource_path('views/themes/' . $theme);

        if (!File::exists($themePath) || !is_dir($themePath)) {
            return back()->with('error', 'Theme not found.');
        }

        $settings->set('active_theme', $theme);

        return back()->with('success', 'Theme activated successfully.');
    }

    /**
     * Create a new theme by copying the default theme.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $name = $request->name;
        $slug = Str::slug($name);

        if ($slug === 'default' || strtolower($name) === 'default') {
            return back()->with('error', 'The theme name cannot be "default".');
        }

        $defaultThemePath = resource_path('views/themes/default');
        $newThemePath = resource_path('views/themes/' . $slug);

        if (File::exists($newThemePath)) {
            return back()->with('error', 'A theme with this slug already exists.');
        }

        if (!File::exists($defaultThemePath)) {
            return back()->with('error', 'The default theme could not be found to copy.');
        }

        File::copyDirectory($defaultThemePath, $newThemePath);

        $jsonPath = $newThemePath . '/theme.json';
        if (File::exists($jsonPath)) {
            $jsonContent = json_decode(File::get($jsonPath), true);
            if (is_array($jsonContent)) {
                $jsonContent['name'] = $name;
                File::put($jsonPath, json_encode($jsonContent, JSON_PRETTY_PRINT));
            }
        }

        return back()->with('success', "Theme '{$name}' created successfully!");
    }

    /**
     * Delete a theme.
     */
    public function destroy($theme, SettingsService $settings): RedirectResponse
    {
        if ($theme === 'default') {
            return back()->with('error', 'The default theme cannot be deleted.');
        }

        $activeTheme = $settings->get('active_theme', 'default');
        if ($theme === $activeTheme) {
            return back()->with('error', 'You cannot delete the active theme.');
        }

        $themePath = resource_path('views/themes/' . $theme);

        if (!File::exists($themePath)) {
            return back()->with('error', 'Theme not found.');
        }

        File::deleteDirectory($themePath);

        return back()->with('success', 'Theme deleted successfully.');
    }

    /**
     * Show the theme editor.
     */
    public function edit(string $theme, Request $request): View|RedirectResponse
    {
        $themePath = resource_path('views/themes/' . $theme);
        if (!File::exists($themePath)) {
            return redirect()->route('admin.themes.index')->with('error', 'Theme not found.');
        }

        $files = $this->getBladeFiles($themePath);
        $selectedFile = $request->query('file');
        $fileContent = '';

        if ($selectedFile) {
            $filePath = $themePath . '/' . $selectedFile;
            // Security check to prevent directory traversal
            if (!str_starts_with(realpath($filePath), realpath($themePath))) {
                return redirect()->route('admin.themes.edit', $theme)->with('error', 'Invalid file path.');
            }

            if (File::exists($filePath)) {
                $fileContent = File::get($filePath);
            }
        }

        return view('admin.themes.edit', compact('theme', 'files', 'selectedFile', 'fileContent'));
    }

    /**
     * Create a new file in the theme.
     */
    public function storeFile(string $theme, Request $request): RedirectResponse
    {
        $request->validate([
            'filename' => 'required|string|regex:/^[a-zA-Z0-9_\-\.\/]+$/',
        ]);

        $filename = $request->filename;
        if (!str_ends_with($filename, '.blade.php')) {
            $filename .= '.blade.php';
        }

        $themePath = resource_path('views/themes/' . $theme);
        $filePath = $themePath . '/' . $filename;

        // Determine real path of parent directory to check boundary
        $parentPath = realpath(dirname($filePath));
        if (!$parentPath) {
            File::ensureDirectoryExists(dirname($filePath));
            $parentPath = realpath(dirname($filePath));
        }

        if (!$parentPath || !str_starts_with($parentPath, realpath($themePath))) {
            return back()->with('error', 'Invalid file path.');
        }

        if (File::exists($filePath)) {
            return back()->with('error', 'File already exists.');
        }

        File::put($filePath, '');

        return redirect()->route('admin.themes.edit', ['theme' => $theme, 'file' => $filename])
                         ->with('success', 'File created successfully.');
    }

    /**
     * Update a file in the theme.
     */
    public function updateFile(string $theme, Request $request): RedirectResponse
    {
        $request->validate([
            'filename' => 'required|string',
            'content' => 'nullable|string'
        ]);

        $themePath = resource_path('views/themes/' . $theme);
        $filePath = $themePath . '/' . $request->filename;

        if (!File::exists($filePath) || !str_starts_with(realpath($filePath), realpath($themePath))) {
            return back()->with('error', 'Invalid file path.');
        }

        File::put($filePath, $request->input('content', ''));

        return back()->with('success', 'File saved successfully.');
    }

    /**
     * Delete a file from the theme.
     */
    public function destroyFile(string $theme, Request $request): RedirectResponse
    {
        $request->validate([
            'filename' => 'required|string'
        ]);

        $themePath = resource_path('views/themes/' . $theme);
        $filePath = $themePath . '/' . $request->filename;

        if (!File::exists($filePath) || !str_starts_with(realpath($filePath), realpath($themePath))) {
            return back()->with('error', 'Invalid file path.');
        }

        File::delete($filePath);

        return redirect()->route('admin.themes.edit', $theme)->with('success', 'File deleted successfully.');
    }

    /**
     * Get all blade files recursively.
     */
    private function getBladeFiles(string $dir): array
    {
        $files = [];
        if (!File::exists($dir)) return $files;

        foreach (File::allFiles($dir) as $file) {
            if (str_ends_with($file->getFilename(), '.blade.php')) {
                $files[] = str_replace('\\', '/', $file->getRelativePathname());
            }
        }
        return $files;
    }
}
