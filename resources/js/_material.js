import '@material/mwc-button';
import '@material/mwc-drawer';
import '@material/mwc-icon-button';
import '@material/mwc-list';
import '@material/mwc-menu';
import './WebComponents/TopAppBar';
import './WebComponents/MaterialDrawer';

const drawer = document.querySelector('material-drawer');
if (drawer) {
  drawer.parentElement.addEventListener('MDCTopAppBar:nav', () => {
    drawer.open = !drawer.open;
  });
}

window.addEventListener('load', () => {
  $('mwc-menu').each((index, menu) => {
    const buttonLinked = $(menu).attr('trigger');
    const button = buttonLinked ? $(`#${buttonLinked}`) : $(menu).prev();
    button.on('click', () => {
      menu.open = !menu.open;
    });
    menu.anchor = button.get(0);
  });
});

