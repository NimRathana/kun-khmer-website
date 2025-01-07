import { defineStore } from 'pinia';
import { ref, watch, onMounted } from 'vue';

export const Store = defineStore('theme', () => {
    const color = ref(localStorage.getItem('colorChange') || 'primary');
    const theme = ref(localStorage.getItem('theme') || 'light');
    const selectedSkin = ref(localStorage.getItem('selectedSkin') || 'default');
    const selectedLayout = ref(localStorage.getItem('selectedLayout') || 'Vertical');
    const selectedContent = ref(localStorage.getItem('selectedContent') || 'Compact');
    const selectedDirection = ref(localStorage.getItem('selectedDirection') || 'Left');
    const menuItem = ref(localStorage.getItem('menuItem') || []);

    const setColor = (newColor) => {
      color.value = newColor;
      localStorage.setItem('colorChange', newColor);
    };

    const setTheme = (newTheme) => {
        theme.value = newTheme;
        localStorage.setItem('theme', newTheme);
    };

    const setSelectedSkin = (skin) => {
        selectedSkin.value = skin;
        localStorage.setItem('selectedSkin', skin);
    };

    const setSelectedLayout = (layout) => {
        selectedLayout.value = layout;
        localStorage.setItem('selectedLayout', layout);
    };

    const setSelectedContent = (content) => {
        selectedContent.value = content;
        localStorage.setItem('selectedContent', content);
    };

    const setSelectedDirection = (direction) => {
        selectedDirection.value = direction;
        localStorage.setItem('selectedDirection', direction);
    };

    const setMenuItem = (item) => {
        menuItem.value = item;
        localStorage.setItem('menuItem', item);
    }

    return { color, setColor, theme, setTheme, selectedSkin, setSelectedSkin, selectedLayout, setSelectedLayout, selectedContent, setSelectedContent, selectedDirection, setSelectedDirection, menuItem, setMenuItem };
});

