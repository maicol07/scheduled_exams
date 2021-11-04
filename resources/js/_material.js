import '@material/mwc-button';
import '@material/mwc-dialog';
import '@material/mwc-drawer';
import '@material/mwc-icon-button';
import '@material/mwc-list';
import '@material/mwc-menu';
import './WebComponents/TopAppBar';
import './WebComponents/MaterialDrawer';
import type {Dialog} from "@material/mwc-dialog";
import type {Menu} from "@material/mwc-menu";

const drawer = document.querySelector('material-drawer');
if (drawer) {
  drawer.parentElement.addEventListener('MDCTopAppBar:nav', () => {
    drawer.open = !drawer.open;
  });
}

window.addEventListener('load', () => {
  $('mwc-menu, mwc-dialog').each((index, element: Dialog | Menu) => {
    const buttonLinked = $(element).attr('trigger');
    const button = buttonLinked ? $(`#${buttonLinked}`) : $(element).prev();
    button.on('click', () => {
      element.open ? element.close() : element.show();
    });
    element.anchor = button.get(0);
  });
});

