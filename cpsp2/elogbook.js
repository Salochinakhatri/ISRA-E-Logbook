(function () {
    "use strict";

    function qs(sel, root) {
        return (root || document).querySelector(sel);
    }

    function qsa(sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    }

    var layout = qs(".elog-layout");
    if (!layout) {
        return;
    }

    var sidebar = qs("#elogSidebar");
    var backdrop = qs("#elogBackdrop");
    var openBtn = qs("#elogMenuOpen");
    var trainingToggle = qs("#trainingToggle");
    var trainingGroup = trainingToggle ? trainingToggle.closest(".elog-sidebar__group") : null;
    var trainingSub = qs("#trainingSubnav");
    var topbarToggle = qs('#elogTopbarToggle');
    var topbarNav = qs('.elog-topbar__nav');

    function setMobileOpen(open) {
        if (!sidebar || !backdrop || !openBtn) {
            return;
        }
        if (open) {
            sidebar.classList.add("is-mobile-open");
            backdrop.hidden = false;
            openBtn.setAttribute("aria-expanded", "true");
        } else {
            sidebar.classList.remove("is-mobile-open");
            backdrop.hidden = true;
            openBtn.setAttribute("aria-expanded", "false");
        }
    }

    function toggleCollapsed(desktop) {
        if (!sidebar || !openBtn) return;
        if (desktop) {
            var collapsed = sidebar.classList.toggle('is-collapsed');
            openBtn.setAttribute('aria-pressed', collapsed ? 'true' : 'false');
            // swap icon to chevron when collapsed on desktop
            var i = openBtn.querySelector('i');
            if (i) {
                if (collapsed) {
                    i.classList.remove('fa-bars');
                    i.classList.add('fa-chevron-right');
                } else {
                    i.classList.remove('fa-chevron-right');
                    i.classList.add('fa-bars');
                }
            }
        }
    }

    if (openBtn && backdrop) {
        openBtn.addEventListener("click", function (e) {
            // if mobile viewport, toggle mobile open, else collapse sidebar
            var isMobile = window.matchMedia('(max-width: 900px)').matches;
            if (isMobile) {
                var open = !sidebar.classList.contains("is-mobile-open");
                setMobileOpen(open);
            } else {
                toggleCollapsed(true);
            }
        });
        backdrop.addEventListener("click", function () {
            setMobileOpen(false);
        });
    }

    // Generic sidebar group toggles (handles training and newly added groups)
    qsa('.elog-sidebar__parent').forEach(function (btn) {
        var grp = btn.closest('.elog-sidebar__group');
        if (!grp) return;
        btn.addEventListener('click', function () {
            var isOpen = grp.classList.contains('is-open');
            // close other groups (accordion behavior)
            qsa('.elog-sidebar__group').forEach(function (g) {
                if (g !== grp) {
                    g.classList.remove('is-open');
                    var pb = g.querySelector('.elog-sidebar__parent');
                    if (pb) pb.setAttribute('aria-expanded', 'false');
                }
            });
            var open = grp.classList.toggle('is-open', !isOpen);
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    });

    // single button now handles collapse on desktop and mobile open on small screens

    if (topbarToggle && topbarNav) {
        topbarToggle.addEventListener('click', function (e) {
            var open = topbarNav.classList.toggle('is-open');
            topbarToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            e.stopPropagation();
        });

        // close when clicking outside
        document.addEventListener('click', function (e) {
            if (!topbarNav.classList.contains('is-open')) {
                return;
            }
            var tgt = e.target;
            if (!topbarNav.contains(tgt) && tgt !== topbarToggle && !topbarToggle.contains(tgt)) {
                topbarNav.classList.remove('is-open');
                topbarToggle.setAttribute('aria-expanded', 'false');
            }
        });

        // close when a link inside is clicked
        topbarNav.addEventListener('click', function (e) {
            var a = e.target.closest('a');
            if (a) {
                topbarNav.classList.remove('is-open');
                topbarToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    /* Flatpickr for British dates */
    if (typeof flatpickr !== "undefined") {
        qsa(".dateBritish").forEach(function (el) {
            flatpickr(el, {
                dateFormat: "d-m-Y",
                allowInput: false,
                clickOpens: true
            });
        });
    }

    /* Competency tree */
    var root = qs("#competencyTreeRoot");
    if (root) {
        function branchForToggle(toggle) {
            var li = toggle.closest(".competency-tree__node--parent");
            return li ? li.querySelector(":scope > .competency-tree__branch") : null;
        }

        function setExpanded(toggle, expanded) {
            var br = branchForToggle(toggle);
            if (!br) {
                return;
            }
            toggle.setAttribute("aria-expanded", expanded ? "true" : "false");
            toggle.textContent = expanded ? "−" : "+";
            if (expanded) {
                br.classList.add("is-open");
            } else {
                br.classList.remove("is-open");
            }
        }

        root.addEventListener("click", function (e) {
            var t = e.target.closest("[data-toggle-branch]");
            if (!t || !root.contains(t)) {
                return;
            }
            e.preventDefault();
            var exp = t.getAttribute("aria-expanded") === "true";
            setExpanded(t, !exp);
        });

        root.addEventListener("keydown", function (e) {
            var t = e.target.closest("[data-toggle-branch]");
            if (!t || (e.key !== "Enter" && e.key !== " ")) {
                return;
            }
            e.preventDefault();
            var exp = t.getAttribute("aria-expanded") === "true";
            setExpanded(t, !exp);
        });

        function allToggles() {
            return qsa("[data-toggle-branch]", root);
        }

        var expandAll = qs("#compExpandAll");
        var collapseAll = qs("#compCollapseAll");
        var defBtn = qs("#compDefault");

        if (expandAll) {
            expandAll.addEventListener("click", function () {
                allToggles().forEach(function (tg) {
                    setExpanded(tg, true);
                });
            });
        }
        if (collapseAll) {
            collapseAll.addEventListener("click", function () {
                allToggles().forEach(function (tg) {
                    setExpanded(tg, false);
                });
            });
        }
        if (defBtn) {
            defBtn.addEventListener("click", function () {
                allToggles().forEach(function (tg) {
                    setExpanded(tg, false);
                });
                qsa('input[name="com_id[]"], input[name="com_detail_id[]"], input[name="rot_id[]"], input[name="rot_detail_id[]"]', root).forEach(function (cb) {
                    cb.checked = false;
                });
            });
        }

        if (window.CPSP_FORM_OLD) {
            var oc = window.CPSP_FORM_OLD.com_id || window.CPSP_FORM_OLD.rot_id || [];
            var od = window.CPSP_FORM_OLD.com_detail_id || window.CPSP_FORM_OLD.rot_detail_id || [];
            oc.forEach(function (id) {
                var inp = root.querySelector('input[name="com_id[]"][value="' + String(id) + '"]') || root.querySelector('input[name="rot_id[]"][value="' + String(id) + '"]');
                if (inp) {
                    inp.checked = true;
                }
            });
            od.forEach(function (id) {
                var inp2 = root.querySelector('input[name="com_detail_id[]"][value="' + String(id) + '"]') || root.querySelector('input[name="rot_detail_id[]"][value="' + String(id) + '"]');
                if (inp2) {
                    inp2.checked = true;
                    var li = inp2.closest(".competency-tree__node--parent");
                    if (li) {
                        var tg = li.querySelector("[data-toggle-branch]");
                        if (tg) {
                            setExpanded(tg, true);
                        }
                    }
                }
            });
        }
    }

    // Prevent double form submission on all forms with class elog-form
    qsa("form.elog-form").forEach(function (form) {
        form.addEventListener("submit", function (e) {
            // Trigger TinyMCE save to update textarea values
            try {
                if (typeof tinymce !== "undefined" && tinymce.triggerSave) {
                    tinymce.triggerSave();
                }
            } catch (err) {
                /* ignore */
            }

            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return false;
                }
                // Short timeout to let the submit event bubble/propagate before disabling
                setTimeout(function () {
                    submitBtn.disabled = true;
                    var textNode = Array.prototype.slice.call(submitBtn.childNodes).filter(function(node) {
                        return node.nodeType === Node.TEXT_NODE;
                    })[0];
                    if (textNode) {
                        textNode.textContent = " Submitting...";
                    }
                }, 10);
            }
        });
    });

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
})();
