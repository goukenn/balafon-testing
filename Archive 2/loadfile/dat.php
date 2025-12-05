<?php
use IGK\Helper\IO;
$f1 = '/Volumes/Data/Downloads/cork-vue-v2.0.1/vue3/src/components/winui/DashbaordUsersTableView.vue';
$f2 = '/Volumes/Data/Downloads/cork-vue-v2.0.1/vue3/src/components/winui/DashbaordSubUserMenuActionView.vue';
igk_wln_e("relative ", IO::GetRelativePath($f1, $f2), igk_io_get_relativepath($f1, $f2));
//igk_wln_e("relative ", IO::GetRelativePath($f1, $f2));
$src =<<<'JS'

<template>

                                                <textarea id="aboutBio" placeholder="Tell something interesting about yourself" rows="10" class="form-control" style="height: 243px">
I'm                                            </textarea>
</template>
<script setup>
    import { ref } from 'vue';
    import '/assets/sass/scrollspyNav.scss';
    import '/assets/sass/users/account-setting.scss';

    import { useMeta } from '/composables/UseMetaComponent';
    useMeta({ title: 'Account Setting' });

    const selected = ref(null);
    const selected_file = ref(null);
    const ddl_1 = ref('20');
    const ddl_2 = ref('Jan');
    const ddl_3 = ref('1989');
    const ddl_4 = ref('May');
    const ddl_5 = ref('2009');
    const ddl_6 = ref('United States');
    const months = ref(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']);
    const range_1 = ref(25);
    const range_2 = ref(50);
    const range_3 = ref(70);
    const range_4 = ref(60);

    const change_file = (event) => {
        selected_file.value = URL.createObjectURL(event.target.files[0]);
    };

    const save = () => {
        showMessage('Settings saved successfully.');
    };

    const showMessage = (msg = '', type = 'success') => {
        const toast = window.Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
        });
        toast.fire({
            icon: type,
            title: msg,
            padding: '10px 20px',
        });
    };
</script>

JS;
$t = igk_create_node('div');
$t->load($src);
igk_wln_e($t->render());