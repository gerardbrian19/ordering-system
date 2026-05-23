    </div><!-- end page content -->
</div><!-- end main content wrapper -->

<script>
// ── Sidebar toggle (mobile) ────────────────────────────────────────────────
const sidebarToggle  = document.getElementById('sidebar-toggle');
const adminSidebar   = document.getElementById('admin-sidebar');
const sidebarBackdrop = document.getElementById('sidebar-backdrop');

function openSidebar() {
    adminSidebar.classList.remove('-translate-x-full');
    sidebarBackdrop.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    adminSidebar.classList.add('-translate-x-full');
    sidebarBackdrop.classList.add('hidden');
    document.body.style.overflow = '';
}

sidebarToggle?.addEventListener('click', openSidebar);
sidebarBackdrop?.addEventListener('click', closeSidebar);

// ── Flash message auto-dismiss ─────────────────────────────────────────────
const flashMsg = document.getElementById('flash-message');
if (flashMsg) {
    setTimeout(() => {
        flashMsg.style.transition = 'opacity 0.5s';
        flashMsg.style.opacity = '0';
        setTimeout(() => flashMsg.remove(), 500);
    }, 4000);
}
</script>
</body>
</html>
