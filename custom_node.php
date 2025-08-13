<?php
$t = igk_create_node('div');
igk_debug(1);
$t->load(<<<EOF
<div class="event_text text-center">
    <igk:horizontalpane class="car-slider presentation-loader" id="slider" @item-changed="onCarDetailSliderChanged" igk-data="{&quot;autoAnim&quot;:false}">
        <igk:page v-for="(pic, index) of car.pictures" :key="index">
            <StorageImg :path="pic.path" />
        </igk:page>
        <EBCFullScreenGalleryButton @full-screen="openFullScreenDialog">
            <div class="dispab loc_b loc_r">
                <igk:google-icon-outlined igk:args="fullscreen" class="md-48" v-pre="v-pre" />
            </div>
        </EBCFullScreenGalleryButton>
    </igk:horizontalpane>
    <PictureThumbNailContainer :pictures="car.pictures" :selectedIndex="selectedIndex" class="alignc" @item-click="onItemClick"></PictureThumbNailContainer>
</div>
EOF);
igk_debug(1);
$t->renderAJX();
exit;