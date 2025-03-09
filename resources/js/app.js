/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import { createApp } from 'vue';

require('./bootstrap');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))



let app=createApp({})
app.component('example-component', require('./components/ExampleComponent.vue').default);

app.mount("#app")

import $ from 'jquery';

window.$ = $;

import 'select2/dist/css/select2.css';

import 'select2';



$('.select2').select2({
    placeholder: "Select an option",
    allowClear: true
});

$('#residenciadepto').change(function() {
    const residenciadepto = $(this).val();

    if (residenciadepto) {
        $.ajax({
            url: `/encuesta/municipios/${residenciadepto}`,
            type: 'GET',
            success: function(data) {
                $('#residenciaciudad').empty().append('<option value="" selected disable>Seleccione municipio</option>');
                data.forEach(function(residenciaciudad) {
                    $('#residenciaciudad').append(`<option value="${residenciaciudad.municipio}" {{old('residenciaciudad') == '${residenciaciudad.municipio}'? 'selected' : ''}}>${residenciaciudad.municipio}</option>`);
                });
                $('#residenciaciudad').prop('disabled', false).trigger('change');
            },
            error: function() {
                alert('No se pueden obtener los municipios.');
            }
        });
    } else {
        $('#residenciaciudad').empty().append('<option value="" selected disable>Seleccione municipio</option>');
        $('#residenciaciudad').prop('disabled', true).trigger('change');
    }
});

