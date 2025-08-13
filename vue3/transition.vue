<template>
    <div class="smap">
        <router-view v-slot="{Component}">
            <Transition>
                <component :is="Component"></component>
            </Transition>
        </router-view>
    </div>
</template>
