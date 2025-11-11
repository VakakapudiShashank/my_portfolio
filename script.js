// script.js

/**
 * NEW: Combined window.onload event
 * This single event handles all script initializations
 * AFTER the pre-loader has finished.
 */
window.onload = function() {
    
    // --- 1. RUN BOOT SEQUENCE ---
    runBootSequence(); 
    
    // --- 2. INITIALIZE ALL OTHER SCRIPTS ---
    // These functions will be called by runBootSequence after it's done.
    initHamburger();
    initScrollReveal();
    initCarousel();
    initParticles();
    initTerminalTypewriter();
};


/**
 * 1. BOOT SEQUENCE
 * This runs the pre-loader animation.
 */
function runBootSequence() {
    const bootText = document.getElementById('boot-text');
    const bootCursor = document.getElementById('boot-cursor');
    
    const lines = [
        "Booting up system...",
        "Loading portfolio assets...",
        "Initializing particle field...",
        "Welcome, Shashank."
    ];
    
    let lineIndex = 0;

    function typeLine() {
        if (lineIndex < lines.length) {
            bootText.innerHTML += `> ${lines[lineIndex]}<br>`;
            lineIndex++;
            setTimeout(typeLine, 300 + Math.random() * 200); // Add a small random delay
        } else {
            // Animation finished
            bootCursor.style.display = 'none'; // Hide cursor
            setTimeout(() => {
                // Add 'loaded' class to the body to trigger fade-out
                document.body.classList.add('loaded');
            }, 500); // Wait 0.5s after last line
        }
    }
    
    // Start the boot sequence
    setTimeout(typeLine, 500);
}


/**
 * 2. HAMBURGER MENU
 * This code controls the mobile navigation menu.
 */
function initHamburger() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');

    if (hamburger && navMenu) { // Check if elements exist
        // Toggle menu on hamburger click
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        // Close menu when a link is clicked
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }
}


/**
 * 3. SCROLL REVEAL ANIMATION
 * This code finds all elements with the class 'reveal' and adds the
 * 'visible' class to them when they enter the viewport.
 */
function initScrollReveal() {
    const revealElements = document.querySelectorAll('.reveal');
    const sectionObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        threshold: 0.1
    });
    revealElements.forEach(el => {
        sectionObserver.observe(el);
    });
}


/**
 * 4. PROJECT CAROUSEL SLIDER
 */
function initCarousel() {
    const cards = document.querySelectorAll('.project-section .gallery .cards li');
    const prevButton = document.querySelector('.project-section .gallery .prev');
    const nextButton = document.querySelector('.project-section .gallery .next');
    
    if (cards.length > 0 && prevButton && nextButton) {
        
        const cardCount = cards.length;
        let activeIndex = 0;

        const mainCard = { transform: 'translateX(0) scale(1)', opacity: 1, zIndex: 10 };
        const sideCard = { scale: 0.7, opacity: 0.7, zIndex: 5 };
        const hiddenCard = { scale: 0.5, opacity: 0, zIndex: 1 };
        const xOffset = '15rem';    
        const xOffsetHidden = '25rem';    

        function updateCardPositions() {
            cards.forEach((card, i) => {
                let offset = i - activeIndex;
                if (offset > cardCount / 2) { offset -= cardCount; }
                else if (offset < -cardCount / 2) { offset += cardCount; }

                switch (offset) {
                    case 0:
                        card.style.transform = mainCard.transform;
                        card.style.opacity = mainCard.opacity;
                        card.style.zIndex = mainCard.zIndex;
                        card.classList.add('active-card');
                        break;
                    case 1:
                        card.style.transform = `translateX(${xOffset}) scale(${sideCard.scale})`;
                        card.style.opacity = sideCard.opacity;
                        card.style.zIndex = sideCard.zIndex;
                        card.classList.remove('active-card');
                        break;
                    case -1:
                        card.style.transform = `translateX(-${xOffset}) scale(${sideCard.scale})`;
                        card.style.opacity = sideCard.opacity;
                        card.style.zIndex = sideCard.zIndex;
                        card.classList.remove('active-card');
                        break;
                    default:
                        const x = offset > 1 ? xOffsetHidden : `-${xOffsetHidden}`;
                        card.style.transform = `translateX(${x}) scale(${hiddenCard.scale})`;
                        card.style.opacity = hiddenCard.opacity;
                        card.style.zIndex = hiddenCard.zIndex;
                        card.classList.remove('active-card');
                        break;
                }
            });
        }

        nextButton.addEventListener('click', () => {
            activeIndex = (activeIndex + 1) % cardCount;
            updateCardPositions();
        });
        prevButton.addEventListener('click', () => {
            activeIndex = (activeIndex - 1 + cardCount) % cardCount;
            updateCardPositions();
        });

        updateCardPositions(); // Set initial positions
    } 
}


/**
 * 5. tsParticles Initialization (Multicolor/Repulse Effect)
 */
function initParticles() {
    tsParticles.load("tsparticles", {
        fpsLimit: 60,
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: ["#ff6347", "#ffbd2e", "#8fde5d", "#4682b4", "#9370db"] },
            shape: { type: "circle" },
            opacity: { value: 0.6, random: { enable: true, minimumValue: 0.2 } },
            size: { value: 3, random: { enable: true, minimumValue: 1 } },
            links: {
                enable: true,
                distance: 150,
                color: "random",
                opacity: 0.4,
                width: 1,
            },
            move: {
                enable: true,
                speed: 2,
                direction: "none",
                random: true,
                straight: false,
                out_mode: "out",
                bounce: false,
            },
            twinkle: {
                particles: {
                    enable: true,
                    frequency: 0.05,
                    opacity: 1
                }
            }
        },
        interactivity: {
            detect_on: "window",
            events: {
                onhover: { enable: true, mode: "repulse" },
                onclick: { enable: true, mode: "push" },
                resize: true
            },
            modes: {
                repulse: { distance: 150, duration: 0.4, factor: 100, speed: 1, maxSpeed: 50, easing: "ease-out-quad" },
                push: { particles_nb: 4 },
                bubble: { distance: 150, size: 3, duration: 2, opacity: 0.8 },
            }
        },
        retina_detect: true,
        background: {
            color: 'transparent'
        }
    });
}


/**
 * 6. Main Terminal Typewriter
 */
function initTerminalTypewriter() {
    // This runs after the main fade-in
    setTimeout(() => {
        const lines = [
            { text: "whoami", el: document.querySelector('.type-line-1') },
            { text: "Shashank Vakalapudi", el: document.querySelector('.type-line-2') },
            { text: "Welcome to my portfolio...", el: document.querySelector('.type-line-3') }
        ];
        const cursor = document.querySelector('.terminal-body .cursor');
        
        function typeWriter(lineIndex) {
            if (lineIndex >= lines.length) {
                if (cursor) cursor.style.display = 'inline-block'; // Show cursor at the end
                return; // All lines are typed
            }

            let i = 0;
            const currentLine = lines[lineIndex];
            const text = currentLine.text;
            const element = currentLine.el;
            
            // Make the parent <p> tag visible
            if (element && element.parentElement) {
                element.parentElement.classList.add('line-visible');
            }

            function type() {
                if (i < text.length) {
                    if (element) element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, 75); // Typing speed
                } else {
                    // Move to the next line
                    typeWriter(lineIndex + 1);
                }
            }
            // Check if element exists before typing
            if (element) {
                type(); // Start typing this line
            } else {
                // If element doesn't exist, skip to next line
                typeWriter(lineIndex + 1);
            }
        }

        // Start the typing cascade from the *second* line
        // (The first line, "whoami", is already visible)
        typeWriter(1); 

    }, 600); // 600ms = 0.5s fade + 0.1s buffer
}