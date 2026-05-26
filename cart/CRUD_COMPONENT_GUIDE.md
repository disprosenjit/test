# CRUD Actions Component Guide

## Overview

A reusable Blade component for displaying admin CRUD actions (View, Edit, Delete) with proper icons and a confirmation modal for delete operations.

## Component Files

- **`resources/views/components/crud-actions.blade.php`** - Main CRUD actions component
- **`resources/views/components/delete-confirmation-modal.blade.php`** - Reusable delete confirmation modal

## Features

- ✅ View action (eye icon)
- ✏️ Edit action (pencil icon)
- 🗑️ Delete action (trash icon)
- 🔒 Confirmation modal before deletion
- 🎨 Tailwind CSS styling with hover effects
- 📱 Responsive design
- 🎯 Customizable messages
- 🔧 Adjustable icon sizes (sm, md, lg)

## Basic Usage

### In Your Blade Template

```blade
<x-crud-actions
    :viewRoute="route('admin.pages.show', $page)"
    :editRoute="route('admin.pages.edit', $page)"
    :deleteRoute="route('admin.pages.destroy', $page)"
/>
```

### Don't Forget the Modal!

Add the delete confirmation modal at the end of your page:

```blade
{{-- Delete Confirmation Modal --}}
<x-delete-confirmation-modal />
@endsection
```

## Component Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `viewRoute` | string | null | Route for view action. If null, view button is hidden |
| `editRoute` | string | null | Route for edit action. If null, edit button is hidden |
| `deleteRoute` | string | null | Route for delete action. If null, delete button is hidden |
| `deleteMessage` | string | "Are you sure you want to delete this item?" | Custom confirmation message |
| `size` | string | "sm" | Icon size: "sm", "md", or "lg" |

## Examples

### Example 1: All Actions

```blade
<x-crud-actions
    :viewRoute="route('admin.products.show', $product)"
    :editRoute="route('admin.products.edit', $product)"
    :deleteRoute="route('admin.products.destroy', $product)"
    deleteMessage="Are you sure you want to delete this product? All related data will be affected."
    size="md"
/>
```

### Example 2: Only View and Edit (No Delete)

```blade
<x-crud-actions
    :viewRoute="route('admin.brands.show', $brand)"
    :editRoute="route('admin.brands.edit', $brand)"
/>
```

### Example 3: Large Icons

```blade
<x-crud-actions
    :viewRoute="route('admin.orders.show', $order)"
    :editRoute="route('admin.orders.edit', $order)"
    :deleteRoute="route('admin.orders.destroy', $order)"
    size="lg"
/>
```

## Table Row Example

```blade
<table class="w-full">
    <thead class="bg-gray-50 border-b-2 border-gray-200">
        <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        @foreach($items as $item)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->status }}</td>
                <td class="px-6 py-4 text-center">
                    <x-crud-actions
                        :viewRoute="route('admin.items.show', $item)"
                        :editRoute="route('admin.items.edit', $item)"
                        :deleteRoute="route('admin.items.destroy', $item)"
                    />
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Modal must be included once per page --}}
<x-delete-confirmation-modal />
```

## Implementation Checklist

For each admin listing page you want to update:

- [ ] Replace inline action buttons with `<x-crud-actions>`
- [ ] Update route names to match your routes
- [ ] Add custom delete message if needed
- [ ] Add `<x-delete-confirmation-modal />` at the end of the page (only once per page)

## Pages to Update

The following admin listing pages should use this component:

- [x] Pages (`resources/views/admin/pages/index.blade.php`) - ✅ Already updated
- [ ] Products (`resources/views/admin/products/index.blade.php`)
- [ ] Categories (`resources/views/admin/categories/index.blade.php`)
- [ ] Brands (`resources/views/admin/brands/index.blade.php`)
- [ ] Orders (`resources/views/admin/orders/index.blade.php`)
- [ ] Inventory (`resources/views/admin/inventory/index.blade.php`)
- [ ] Payments (`resources/views/admin/payments/index.blade.php`)

## Styling & Icons

The component uses:
- **Icons**: Font Awesome 6 (fas fa-eye, fas fa-edit, fas fa-trash, fas fa-exclamation-triangle)
- **Styling**: Tailwind CSS with custom hover effects
- **Colors**:
  - View: Blue (#3B82F6)
  - Edit: Yellow (#CA8A04)
  - Delete: Red (#DC2626)

## How It Works

1. **Component Props**: Pass the action routes to the component
2. **Form Generation**: The component creates hidden forms for POST/DELETE actions
3. **Modal Trigger**: Delete button triggers the confirmation modal
4. **Form Submission**: Confirmed delete submits the appropriate form
5. **Cleanup**: Modal closes on cancel or submission

## JavaScript Functions

The component uses these JavaScript functions:

- `openDeleteModal(formId, message)` - Opens the confirmation modal
- `closeDeleteModal()` - Closes the confirmation modal
- Modal closes automatically when clicking outside (backdrop)

## Browser Compatibility

Works on all modern browsers:
- Chrome/Edge 90+
- Firefox 88+
- Safari 14+

## Customization

To customize colors or styling, edit the component files:

- **Colors**: Modify `text-blue-600`, `text-yellow-600`, `text-red-600` classes
- **Hover Effects**: Adjust `hover:bg-blue-50`, `hover:text-blue-800` classes
- **Modal Styling**: Edit the modal component colors and transitions
