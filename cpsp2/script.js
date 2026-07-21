(function () {
    "use strict";

    function qs(sel, root) {
        return (root || document).querySelector(sel);
    }

    function qsa(sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    }

    /* Scroll to top */
    var scrollBtn = qs("#scrollTop");
    if (scrollBtn) {
        function toggleScrollBtn() {
            if (window.scrollY > 200) {
                scrollBtn.classList.add("is-visible");
            } else {
                scrollBtn.classList.remove("is-visible");
            }
        }
        window.addEventListener("scroll", toggleScrollBtn, { passive: true });
        toggleScrollBtn();
        scrollBtn.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    /* Forgot password modal */
    var forgotBtn = qs("#btnForgot");
    var modal = qs("#forgotModal");
    if (forgotBtn && modal) {
        function openModal() {
            modal.hidden = false;
            document.body.style.overflow = "hidden";
        }
        function closeModal() {
            modal.hidden = true;
            document.body.style.overflow = "";
        }
        forgotBtn.addEventListener("click", openModal);
        qsa("[data-close-modal]", modal).forEach(function (el) {
            el.addEventListener("click", closeModal);
        });
        modal.addEventListener("keydown", function (e) {
            if (e.key === "Escape") {
                closeModal();
            }
        });
    }

    /* Custom Logout Modal */
    var logoutModal = qs("#logoutModal");
    if (logoutModal) {
        function closeLogoutModal() {
            logoutModal.hidden = true;
            document.body.style.overflow = "";
        }
        qsa(".js-logout-confirm").forEach(function (link) {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                logoutModal.hidden = false;
                document.body.style.overflow = "hidden";
            });
        });
        qsa("[data-close-modal]", logoutModal).forEach(function (el) {
            el.addEventListener("click", closeLogoutModal);
        });
        logoutModal.addEventListener("keydown", function (e) {
            if (e.key === "Escape") {
                closeLogoutModal();
            }
        });
    } else {
        qsa(".js-logout-confirm").forEach(function (link) {
            link.addEventListener("click", function (e) {
                var ok = window.confirm("Are you sure you want to logout from CPSP e-Logbook?");
                if (!ok) {
                    e.preventDefault();
                }
            });
        });
    }

    /* Login form validation */
    var form = qs("#loginForm");
    if (form) {
        function clearErrors() {
            qsa(".field-error", form).forEach(function (n) {
                n.remove();
            });
            qsa(".is-invalid", form).forEach(function (el) {
                el.classList.remove("is-invalid");
            });
        }

        function showError(input, message) {
            var group = input.closest(".form-group");
            if (!group) {
                return;
            }
            var target = input.closest(".input-icon") || input;
            target.classList.add("is-invalid");
            var err = document.createElement("span");
            err.className = "field-error";
            err.textContent = message;
            group.appendChild(err);
        }

        form.addEventListener("submit", function (e) {
            clearErrors();
            var type = qs("#user_type_id", form);
            var user = qs("#username", form);
            var pass = qs("#password", form);
            var ok = true;

            if (!type || !type.value) {
                e.preventDefault();
                if (type) {
                    showError(type, "Please select a user type.");
                }
                ok = false;
            }
            if (!user || !user.value.trim()) {
                e.preventDefault();
                if (user) {
                    showError(user, "Please enter your username.");
                }
                ok = false;
            }
            if (!pass || !pass.value) {
                e.preventDefault();
                if (pass) {
                    showError(pass, "Please enter your password.");
                }
                ok = false;
            }
            if (!ok) {
                return;
            }
        });

        qsa(".form-control", form).forEach(function (input) {
            input.addEventListener("input", function () {
                var group = input.closest(".form-group");
                if (!group) {
                    return;
                }
                var err = qs(".field-error", group);
                var wrap = input.closest(".input-icon");
                if (wrap) {
                    wrap.classList.remove("is-invalid");
                }
                input.classList.remove("is-invalid");
                if (err) {
                    err.remove();
                }
            });
        });
    }
})();
