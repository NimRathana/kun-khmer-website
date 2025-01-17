import $ from 'jquery';
import { Store } from '@/store/index';

export const helper = {
    GetGridHeight() {
        const store = Store();
        // Get toolbar header height
        var toolbarElement = document.querySelector(".v-toolbar .v-toolbar__content");
        var toolbarHeader = toolbarElement ? toolbarElement.offsetHeight : 0;

        // Get search row height
        var rowSearchElement = document.querySelector(".txtsearch");
        var rowSearch = rowSearchElement ? rowSearchElement.offsetHeight + 10 : 0;

        var toolbarExtension = null;
        var toolbarExtensionHeight = 0;
        if(store.selectedLayout == "Horizontal"){
            toolbarExtension = document.querySelector(".v-toolbar .v-toolbar__extension");
            toolbarExtensionHeight = toolbarExtension ? toolbarExtension.offsetHeight : 0;
        }

        // Get the table element
        var tableElement = document.querySelector(".v-table");
        if (tableElement) {
            tableElement.style.height = `calc(100vh - ${toolbarHeader + rowSearch + toolbarExtensionHeight}px)`;
        } else {
            console.log("Table element not found");
        }
    }
};

