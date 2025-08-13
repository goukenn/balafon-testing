 <teleport to="#mobile-menu">
        <igk:google_icon_outlined class="igk-btn" igk:args="menu" data-bs-toggle="offcanvas" data-bs-target="#modal-menu"/>
    </teleport>
    <teleport to="body">
        <igk:view-include igk:args="@Layout/Components/offcanvas_menu" />
    </teleport>