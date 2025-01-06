import $ from 'jquery';

export const helper = {
  GetGridHeight() {
    // Get the header element
    const headElement = document.getElementsByClassName("head")[0];
    if (headElement) {
      const height = headElement.offsetHeight - 20; // Subtracting 20 for padding/margin adjustments
      // Select the grid container and adjust its height
      const gridHeight = document.getElementsByClassName("v-table")[0];
      if (gridHeight) {
        gridHeight.style.height = `calc(100% - ${height}px)`;
      } else {
        console.warn(`Grid element with class "v-table" not found.`);
      }
    } else {
      console.warn(`Header element with class "head" not found.`);
    }
  },
};

