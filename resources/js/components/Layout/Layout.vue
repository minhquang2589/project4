<template>
    <div>
        <Header v-if="!$route.meta.hideHeader" />
        <Section v-if="$route.meta.showSection" />
        <Slider v-if="$route.meta.showSlider" />
        <SliderSale v-if="$route.meta.showSliderSale" />
        <SliderRecently v-if="$route.meta.showSliderRecently" />
        
        <main>
            <router-view />
        </main>
        <div class="hidden_navi">
            <Navigatiion v-if="!$route.meta.hideNavigatiion" />
        </div>

        <Footer v-if="!$route.meta.hideFooter" />
    </div>
</template>

<script>
import Header from "./Header.vue";
import Footer from "./Footer.vue";
import Navigatiion from "./navigation/Navigation.vue";
import Slider from "../slider/Slider.vue";
import SliderSale from "../slider/SliderSale.vue";
import SliderRecently from "../slider/recentlyProducts.vue";
import Section from "../section/Section.vue";

export default {
    components: {
        Header,
        Footer,
        Navigatiion,
        Slider,
        Section,
        SliderSale,
        SliderRecently,
    },
};
</script>
