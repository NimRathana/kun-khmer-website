import $ from 'jquery';

export const helper = {
  GetGridHeight() {
    // Get the header element
    const headElement = document.getElementsByClassName("head")[0];
    if (headElement) {
        const height = headElement.offsetHeight; // Subtracting 20 for padding/margin adjustments
        // Select the grid container and adjust its height
        const bodyHeight = document.getElementsByClassName("body")[0];
        if (bodyHeight) {
            bodyHeight.style.height = `calc(100% - ${height}px)`;
        }
    }
  },
};

