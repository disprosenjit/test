/**
 * WEBSOLAI Website Interaction Script
 * Premium UX features: Canvas Animations, Scroll Watchers, Validation, Typist Carousel
 */

$(document).ready(function() {

    /* ==========================================================================
       1. GLOBAL & HERO CANVAS PARTICLES
       ========================================================================== */
    initGlobalParticles();
    initHeroNeuralNetwork();

    /* ==========================================================================
       2. MOUSE MOVEMENT INTERACTION (GLOW EFFECTS)
       ========================================================================== */
    const cursorGlow = document.querySelector('.cursor-glow');
    
    $(document).on('mousemove', function(e) {
        // Move absolute cursor glow spotlight
        if (cursorGlow) {
            cursorGlow.style.left = e.clientX + 'px';
            cursorGlow.style.top = e.clientY + 'px';
        }

        // Send mouse position coordinates to each glass card for localized glowing border gradient
        $('.glass-card').each(function() {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            this.style.setProperty('--mouse-x', `${x}px`);
            this.style.setProperty('--mouse-y', `${y}px`);
        });
    });

    /* ==========================================================================
       3. NAVIGATION BAR & ACTIVE SECTION SYNC
       ========================================================================== */
    const mainNav = $('#main-nav');
    
    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 50) {
            mainNav.addClass('navbar-scrolled');
        } else {
            mainNav.removeClass('navbar-scrolled');
        }
        
        // Dynamic active section scrolling highlighter
        const scrollPos = $(window).scrollTop() + 150;
        $('section, header').each(function() {
            const top = $(this).offset().top;
            const bottom = top + $(this).outerHeight();
            const id = $(this).attr('id');
            
            if (scrollPos >= top && scrollPos <= bottom) {
                $('#navbarContent .nav-link').removeClass('active');
                $(`#navbarContent .nav-link[href="#${id}"]`).addClass('active');
            }
        });
    });

    // Mobile Navbar collapse on click of link
    $('#navbarContent .nav-link').on('click', function() {
        if ($('.navbar-toggler').is(':visible')) {
            $('.navbar-collapse').collapse('hide');
        }
    });

    /* ==========================================================================
       4. TYPING CAROUSEL ANIMATION
       ========================================================================== */
    const words = [
        "Accelerates Your Business.",
        "Drives Enterprise Innovation.",
        "Automates Workflows.",
        "Scales Your Operations."
    ];
    
    let wordIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    const typedTextSpan = document.getElementById("typed-text");
    
    function typeEffect() {
        if (!typedTextSpan) return;
        
        const currentWord = words[wordIndex];
        
        if (isDeleting) {
            typedTextSpan.textContent = currentWord.substring(0, charIndex - 1);
            charIndex--;
        } else {
            typedTextSpan.textContent = currentWord.substring(0, charIndex + 1);
            charIndex++;
        }
        
        let typeSpeed = isDeleting ? 40 : 80;
        
        if (!isDeleting && charIndex === currentWord.length) {
            // Completed typing word. Pause.
            typeSpeed = 2200;
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            // Completed deleting word. Switch to next.
            isDeleting = false;
            wordIndex = (wordIndex + 1) % words.length;
            typeSpeed = 500;
        }
        
        setTimeout(typeEffect, typeSpeed);
    }
    
    setTimeout(typeEffect, 1000);

    /* ==========================================================================
       5. INTERSECTION OBSERVER FOR FADE-UP ANIMATIONS & PROCESS LINE
       ========================================================================== */
    // Scroll fade-up initializer
    if ('IntersectionObserver' in window) {
        const fadeUpObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-up, .fade-in-up').forEach(el => {
            fadeUpObserver.observe(el);
        });

        // Developer timeline section observer
        const processSection = document.getElementById('process');
        if (processSection) {
            const processObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateTimelineSteps();
                    }
                });
            }, { threshold: 0.25 });
            processObserver.observe(processSection);
        }
    } else {
        // Fallback if IntersectionObserver is not supported
        $('.fade-up').addClass('active');
        animateTimelineSteps();
    }

    function animateTimelineSteps() {
        const steps = $('.timeline-step');
        const progressBar = $('.timeline-progress');
        
        steps.each(function(index) {
            const step = $(this);
            setTimeout(function() {
                step.addClass('active');
                // Increment progress line percentage
                const progressPercentage = ((index + 1) / steps.length) * 100;
                progressBar.css('width', `${progressPercentage}%`);
            }, index * 400); // 400ms sequential animation delay
        });
    }

    /* ==========================================================================
       6. BUTTON RIPPLE CLICK ANIMATION
       ========================================================================== */
    $(document).on('click', '.btn-gradient-cyan, .btn-outline-light', function(e) {
        const button = $(this);
        const ripple = $('<span class="ripple"></span>');
        
        const offset = button.offset();
        const x = e.pageX - offset.left;
        const y = e.pageY - offset.top;
        
        ripple.css({
            top: y + 'px',
            left: x + 'px'
        });
        
        button.append(ripple);
        
        setTimeout(function() {
            ripple.remove();
        }, 600);
    });

    /* ==========================================================================
       7. CONTACT FORM VALIDATION & POPUP
       ========================================================================== */
    const contactForm = document.getElementById('contactForm');
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            if (contactForm.checkValidity()) {
                const name = $('#name').val();
                
                // Customize message inside modal
                $('#successModalMessage').html(`Thank you, <strong>${name}</strong>! Your inquiry has been successfully sent. An enterprise AI consultant will contact you within 2 business hours.`);
                
                // Launch success modal popup
                successModal.show();
                
                // Reset form state
                contactForm.reset();
                contactForm.classList.remove('was-validated');
            } else {
                contactForm.classList.add('was-validated');
            }
        }, false);
    }

    /* ==========================================================================
       8. SMOOTH ANCHOR LINK SCROLLING
       ========================================================================== */
    $('a[href^="#"]').on('click', function(e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 80
            }, 800);
        }
    });

});

/* ==========================================================================
   GLOBAL SLOW-DRIFT BACKGROUND PARTICLES (CANVAS)
   ========================================================================== */
function initGlobalParticles() {
    const canvas = document.getElementById('bg-particles');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;
    
    const particles = [];
    const particleCount = Math.min(60, Math.floor(width / 25)); // Scale with screen resolution
    
    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.15;
            this.vy = (Math.random() - 0.5) * 0.15;
            this.radius = Math.random() * 1.5 + 0.5;
            this.alpha = Math.random() * 0.3 + 0.1;
        }
        
        update() {
            this.x += this.vx;
            this.y += this.vy;
            
            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;
        }
        
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(20, 216, 255, ${this.alpha})`;
            ctx.fill();
        }
    }
    
    // Instantiate particles
    for (let i = 0; i < particleCount; i++) {
        particles.push(new Particle());
    }
    
    function animate() {
        ctx.clearRect(0, 0, width, height);
        particles.forEach(p => {
            p.update();
            p.draw();
        });
        requestAnimationFrame(animate);
    }
    
    animate();
    
    $(window).on('resize', function() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    });
}

/* ==========================================================================
   INTERACTIVE NEURAL NETWORK HERO BACKGROUND (CANVAS)
   ========================================================================== */
function initHeroNeuralNetwork() {
    const canvas = document.getElementById('hero-neural-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    
    const heroSection = document.getElementById('home');
    
    let width = canvas.width = heroSection.clientWidth;
    let height = canvas.height = heroSection.clientHeight;
    
    const nodes = [];
    const nodeCount = Math.min(80, Math.floor(width / 15));
    const maxDistance = 110;
    
    const mouse = {
        x: null,
        y: null,
        radius: 150
    };
    
    // Track mouse moves specifically within hero area
    heroSection.addEventListener('mousemove', function(e) {
        const rect = heroSection.getBoundingClientRect();
        mouse.x = e.clientX - rect.left;
        mouse.y = e.clientY - rect.top;
    });
    
    heroSection.addEventListener('mouseleave', function() {
        mouse.x = null;
        mouse.y = null;
    });
    
    class Node {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.5;
            this.vy = (Math.random() - 0.5) * 0.5;
            this.radius = Math.random() * 2 + 1;
        }
        
        update() {
            this.x += this.vx;
            this.y += this.vy;
            
            // Boundary bouncing
            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;
            
            // Mouse magnet effect
            if (mouse.x !== null && mouse.y !== null) {
                const dx = mouse.x - this.x;
                const dy = mouse.y - this.y;
                const dist = Math.hypot(dx, dy);
                
                if (dist < mouse.radius) {
                    const force = (mouse.radius - dist) / mouse.radius;
                    // Pull nodes slightly towards cursor
                    this.x += (dx / dist) * force * 0.4;
                    this.y += (dy / dist) * force * 0.4;
                }
            }
        }
        
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(20, 216, 255, 0.6)';
            ctx.fill();
        }
    }
    
    // Seed nodes
    for (let i = 0; i < nodeCount; i++) {
        nodes.push(new Node());
    }
    
    function drawConnections() {
        for (let i = 0; i < nodes.length; i++) {
            for (let j = i + 1; j < nodes.length; j++) {
                const dx = nodes[i].x - nodes[j].x;
                const dy = nodes[i].y - nodes[j].y;
                const dist = Math.hypot(dx, dy);
                
                if (dist < maxDistance) {
                    // Line opacity decreases as distance grows
                    const alpha = (1 - (dist / maxDistance)) * 0.15;
                    ctx.beginPath();
                    ctx.moveTo(nodes[i].x, nodes[i].y);
                    ctx.lineTo(nodes[j].x, nodes[j].y);
                    ctx.strokeStyle = `rgba(20, 216, 255, ${alpha})`;
                    ctx.lineWidth = 0.8;
                    ctx.stroke();
                }
            }
            
            // Node connection to cursor
            if (mouse.x !== null && mouse.y !== null) {
                const dx = nodes[i].x - mouse.x;
                const dy = nodes[i].y - mouse.y;
                const dist = Math.hypot(dx, dy);
                
                if (dist < mouse.radius) {
                    const alpha = (1 - (dist / mouse.radius)) * 0.3;
                    ctx.beginPath();
                    ctx.moveTo(nodes[i].x, nodes[i].y);
                    ctx.lineTo(mouse.x, mouse.y);
                    ctx.strokeStyle = `rgba(20, 216, 255, ${alpha})`;
                    ctx.lineWidth = 1.0;
                    ctx.stroke();
                }
            }
        }
    }
    
    function animate() {
        ctx.clearRect(0, 0, width, height);
        
        nodes.forEach(node => {
            node.update();
            node.draw();
        });
        
        drawConnections();
        requestAnimationFrame(animate);
    }
    
    animate();
    
    $(window).on('resize', function() {
        width = canvas.width = heroSection.clientWidth;
        height = canvas.height = heroSection.clientHeight;
    });
}
