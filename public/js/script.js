// ======================================
// MOIST Modern JavaScript
// ======================================

document.addEventListener("DOMContentLoaded", () => {

    /* ===============================
       Mobile Menu Toggle
    =============================== */

    const menuToggle = document.getElementById("menu-toggle");
    const mainNav = document.getElementById("main-nav");

    if (menuToggle && mainNav) {

        menuToggle.addEventListener("click", () => {

            const isOpen = mainNav.classList.toggle("open");

            menuToggle.classList.toggle("active", isOpen);
            menuToggle.setAttribute("aria-expanded", isOpen);

        });

        mainNav.querySelectorAll("a").forEach(link => {

            link.addEventListener("click", () => {

                mainNav.classList.remove("open");
                menuToggle.classList.remove("active");
                menuToggle.setAttribute("aria-expanded", "false");

            });

        });

        window.addEventListener("resize", () => {

            if (window.innerWidth > 860) {

                mainNav.classList.remove("open");
                menuToggle.classList.remove("active");
                menuToggle.setAttribute("aria-expanded", "false");

            }

        });

    }


    /* ===============================
       Smooth Scrolling
    =============================== */

    document.querySelectorAll('nav a[href^="#"]').forEach(anchor => {

        anchor.addEventListener("click", function (e) {

            e.preventDefault();

            const target = document.querySelector(this.getAttribute("href"));

            if (target) {

                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });

            }

        });

    });


    /* ===============================
       Sticky Header Shadow
    =============================== */

    const header = document.querySelector("header");

    if (header) {

        window.addEventListener("scroll", () => {

            if (window.scrollY > 30) {

                header.classList.add("header-shadow");

            } else {

                header.classList.remove("header-shadow");

            }

        });

    }


    /* ===============================
       Scroll Reveal Animation
    =============================== */

    const revealElements = document.querySelectorAll(
        ".hero-video,.hero-search,.welcome-text,.info-courses,.info-news,.footer-column"
    );

    const observer = new IntersectionObserver((entries) => {

        entries.forEach(entry => {

            if (entry.isIntersecting) {

                entry.target.classList.add("show");

            }

        });

    }, {

        threshold: .15

    });

    revealElements.forEach(el => observer.observe(el));


    /* ===============================
       Typing Effect
    =============================== */

    const title = document.getElementById("typing-title");

    if (title) {

        const text = title.textContent.trim();

        title.textContent = "";

        let index = 0;

        function type() {

            if (index < text.length) {

                title.textContent += text.charAt(index);

                index++;

                setTimeout(type, 30);

            }

        }

        type();

    }


    /* ===============================
       Learn More Ripple
    =============================== */

    const learnBtn = document.querySelector(".learn-btn");

    if (learnBtn) {

        learnBtn.addEventListener("click", (e) => {

            const ripple = document.createElement("span");

            ripple.classList.add("ripple");

            const rect = learnBtn.getBoundingClientRect();

            ripple.style.left = e.clientX - rect.left + "px";

            ripple.style.top = e.clientY - rect.top + "px";

            learnBtn.appendChild(ripple);

            setTimeout(() => {

                ripple.remove();

            }, 600);

        });

    }


    /* ===============================
       Active Navigation
    =============================== */

    const sections = document.querySelectorAll("section");

    const navLinks = document.querySelectorAll("nav a");

    window.addEventListener("scroll", () => {

        let current = "";

        sections.forEach(section => {

            const top = section.offsetTop - 120;

            if (window.scrollY >= top) {

                current = section.getAttribute("id");

            }

        });

        navLinks.forEach(link => {

            link.classList.remove("active");

            if (link.getAttribute("href") === "#" + current) {

                link.classList.add("active");

            }

        });

    });


    /* ===============================
       Search Validation
    =============================== */

    const form = document.querySelector(".search-box");

    if (form) {

        form.addEventListener("submit", (e) => {

            const input = form.querySelector("input");

            if (input.value.trim() === "") {

                e.preventDefault();

                input.focus();

                input.style.borderColor = "#d62828";

                return;

            }

            input.style.borderColor = "";

            // Valid query: allow native GET submit to /opac?search=...

        });

    }


    /* ===============================
       Footer Fade
    =============================== */

    const footer = document.querySelector("footer");

    if (footer) {

        const footerObserver = new IntersectionObserver(entries => {

            entries.forEach(entry => {

                if (entry.isIntersecting) {

                    footer.classList.add("show");

                }

            });

        });

        footerObserver.observe(footer);

    }

});
