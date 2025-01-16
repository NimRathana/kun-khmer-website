import $ from 'jquery';

export const helper = {
    GetGridHeight() {
        // Get toolbar header height
        var toolbarElement = document.querySelector(".v-toolbar");
        var toolbarHeader = toolbarElement ? toolbarElement.offsetHeight : 0;

        // Get search row height
        var rowSearchElement = document.querySelector(".txtsearch");
        var rowSearch = rowSearchElement ? rowSearchElement.offsetHeight + 10 : 10;

        // Get the table element
        var tableElement = document.querySelector(".v-table");
        if (tableElement) {
            tableElement.style.height = `calc(100vh - ${toolbarHeader + rowSearch}px)`;
        } else {
            console.log("Table element not found");
        }
    }
};

