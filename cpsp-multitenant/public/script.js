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
                var form = qs("#logoutForm");
                if (form) {
                    e.preventDefault();
                    form.submit();
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

    /* =========================================================================
       Universal Responsive Custom Dropdown Enhancer
       Solves desktop/mobile options overflow, wraps long text, and guarantees
       100% responsive boundaries across all devices and screen widths.
       ========================================================================= */
    function initResponsiveSelects(rootEl) {
        var root = rootEl || document;
        var selects = qsa("select:not(.no-custom-select)", root);

        selects.forEach(function (sel) {
            if (sel.dataset.respEnhanced === "true") {
                return;
            }
            sel.dataset.respEnhanced = "true";

            // Create responsive wrapper
            var wrapper = document.createElement("div");
            wrapper.className = "resp-select-container";

            // Propagate styling context
            if (sel.classList.contains("field__control")) wrapper.classList.add("resp-field-control");
            if (sel.classList.contains("form-select")) wrapper.classList.add("resp-form-select");
            if (sel.classList.contains("elog-age-unit")) wrapper.classList.add("resp-age-unit");
            if (sel.classList.contains("sup-bulk-select")) wrapper.classList.add("resp-bulk-select");

            // Insert wrapper and nest select
            sel.parentNode.insertBefore(wrapper, sel);
            wrapper.appendChild(sel);

            // Hide native select accessibly so native forms/validation still work
            sel.classList.add("resp-select-native");

            // Build custom trigger button
            var trigger = document.createElement("button");
            trigger.type = "button";
            trigger.className = "resp-select-trigger";
            trigger.setAttribute("aria-haspopup", "listbox");
            trigger.setAttribute("aria-expanded", "false");
            if (sel.id) {
                trigger.id = "resp_trigger_" + sel.id;
            }

            var labelSpan = document.createElement("span");
            labelSpan.className = "resp-select-label";

            var arrowSpan = document.createElement("span");
            arrowSpan.className = "resp-select-arrow";
            arrowSpan.setAttribute("aria-hidden", "true");
            arrowSpan.innerHTML = '<i class="fa-solid fa-chevron-down"></i>';

            trigger.appendChild(labelSpan);
            trigger.appendChild(arrowSpan);
            wrapper.appendChild(trigger);

            // Build dropdown list container
            var dropdown = document.createElement("div");
            dropdown.className = "resp-select-dropdown";
            dropdown.setAttribute("role", "listbox");
            wrapper.appendChild(dropdown);

            function refreshItems() {
                dropdown.innerHTML = "";
                var options = qsa("option", sel);
                var activeText = "";

                options.forEach(function (opt) {
                    var item = document.createElement("div");
                    item.className = "resp-select-option";
                    item.setAttribute("role", "option");
                    item.setAttribute("data-value", opt.value);
                    item.textContent = opt.textContent;

                    if (opt.disabled) {
                        item.classList.add("is-disabled");
                    }

                    if (opt.selected) {
                        item.classList.add("is-selected");
                        item.setAttribute("aria-selected", "true");
                        activeText = opt.textContent;
                    } else {
                        item.setAttribute("aria-selected", "false");
                    }

                    item.addEventListener("click", function (e) {
                        e.stopPropagation();
                        if (opt.disabled) return;
                        if (sel.value !== opt.value) {
                            sel.value = opt.value;
                            sel.dispatchEvent(new Event("change", { bubbles: true }));
                            sel.dispatchEvent(new Event("input", { bubbles: true }));
                        }
                        updateLabelAndSelection();
                        closeDropdown();
                        trigger.focus();
                    });

                    dropdown.appendChild(item);
                });

                labelSpan.textContent = activeText || (options[0] ? options[0].textContent : "- Select -");
            }

            function updateLabelAndSelection() {
                var currentVal = sel.value;
                var currentOpt = sel.options[sel.selectedIndex];
                labelSpan.textContent = currentOpt ? currentOpt.textContent : "- Select -";

                qsa(".resp-select-option", dropdown).forEach(function (el) {
                    if (el.getAttribute("data-value") === currentVal) {
                        el.classList.add("is-selected");
                        el.setAttribute("aria-selected", "true");
                    } else {
                        el.classList.remove("is-selected");
                        el.setAttribute("aria-selected", "false");
                    }
                });
                trigger.classList.remove("has-error");
            }

            refreshItems();

            function openDropdown() {
                // Close all other open dropdowns
                qsa(".resp-select-container.is-open").forEach(function (other) {
                    if (other !== wrapper) {
                        other.classList.remove("is-open");
                        var otherTrigger = other.querySelector(".resp-select-trigger");
                        if (otherTrigger) otherTrigger.setAttribute("aria-expanded", "false");
                    }
                });

                // Viewport position check: auto-flip if near bottom
                var rect = wrapper.getBoundingClientRect();
                var spaceBelow = window.innerHeight - rect.bottom;
                var spaceAbove = rect.top;
                if (spaceBelow < 230 && spaceAbove > spaceBelow) {
                    dropdown.classList.add("opens-up");
                } else {
                    dropdown.classList.remove("opens-up");
                }

                wrapper.classList.add("is-open");
                trigger.setAttribute("aria-expanded", "true");

                // Auto-scroll selected item into view
                var selectedItem = dropdown.querySelector(".resp-select-option.is-selected");
                if (selectedItem) {
                    selectedItem.scrollIntoView({ block: "nearest" });
                }
            }

            function closeDropdown() {
                wrapper.classList.remove("is-open");
                trigger.setAttribute("aria-expanded", "false");
                qsa(".resp-select-option.is-focused", dropdown).forEach(function (el) {
                    el.classList.remove("is-focused");
                });
            }

            function toggleDropdown() {
                if (wrapper.classList.contains("is-open")) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            }

            trigger.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggleDropdown();
            });

            // Keyboard navigation
            trigger.addEventListener("keydown", function (e) {
                var isOpen = wrapper.classList.contains("is-open");
                if (e.key === "ArrowDown" || e.key === "ArrowUp") {
                    e.preventDefault();
                    if (!isOpen) {
                        openDropdown();
                        return;
                    }
                    var items = qsa(".resp-select-option:not(.is-disabled)", dropdown);
                    if (!items.length) return;
                    var current = dropdown.querySelector(".resp-select-option.is-focused") || dropdown.querySelector(".resp-select-option.is-selected");
                    var curIdx = items.indexOf(current);
                    if (e.key === "ArrowDown") {
                        curIdx = (curIdx + 1) % items.length;
                    } else {
                        curIdx = (curIdx - 1 + items.length) % items.length;
                    }
                    items.forEach(function (it) { it.classList.remove("is-focused"); });
                    items[curIdx].classList.add("is-focused");
                    items[curIdx].scrollIntoView({ block: "nearest" });
                } else if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    if (isOpen) {
                        var focused = dropdown.querySelector(".resp-select-option.is-focused");
                        if (focused) {
                            focused.click();
                        } else {
                            closeDropdown();
                        }
                    } else {
                        openDropdown();
                    }
                } else if (e.key === "Escape") {
                    closeDropdown();
                } else if (e.key === "Tab") {
                    closeDropdown();
                }
            });

            // Listen for native select changes or programmatic changes
            sel.addEventListener("change", function () {
                updateLabelAndSelection();
            });

            // Handle validation errors gracefully
            sel.addEventListener("invalid", function () {
                trigger.classList.add("has-error");
            });

            // If a label clicks this select, open the custom dropdown
            if (sel.id) {
                var label = document.querySelector('label[for="' + sel.id + '"]');
                if (label) {
                    label.addEventListener("click", function (e) {
                        e.preventDefault();
                        trigger.focus();
                        openDropdown();
                    });
                }
            }
        });
    }

    // Global click listener to close open dropdowns
    document.addEventListener("click", function (e) {
        if (!e.target.closest(".resp-select-container")) {
            qsa(".resp-select-container.is-open").forEach(function (wrap) {
                wrap.classList.remove("is-open");
                var t = wrap.querySelector(".resp-select-trigger");
                if (t) t.setAttribute("aria-expanded", "false");
            });
        }
    });

    // Window resize listener to recalculate positioning or close off-screen menus
    window.addEventListener("resize", function () {
        qsa(".resp-select-container.is-open").forEach(function (wrap) {
            wrap.classList.remove("is-open");
            var t = wrap.querySelector(".resp-select-trigger");
            if (t) t.setAttribute("aria-expanded", "false");
        });
    }, { passive: true });

    // Expose globally and run on DOM ready
    window.initResponsiveSelects = initResponsiveSelects;

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", function () {
            initResponsiveSelects();
        });
    } else {
        initResponsiveSelects();
    }
})();
