
<script setup>
import { computed } from 'vue';
import '@/assets/sass/app.scss';
import { useMeta } from '@/composables/UseMetaComponent';
import { useStore } from 'vuex';
</script>
<script>
    // layouts
    import appLayout from './layouts/AppLayoutComponent.vue';
    import authLayout from './layouts/AuthLayoutComponent.vue';

    export default {
        components: {
            app: appLayout,
            auth: authLayout,
        },
    };
</script>

<script>

useMeta({ title: 'Sales Admin' });

const store = useStore();

const layout = computed(() => {
    return store.getters.layout;
});
</script>