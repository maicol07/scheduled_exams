import '@material/mwc-fab';

import {Inertia} from '@inertiajs/inertia';
import {collect, type Collection} from 'collect.js';

import Mdi from '../Components/Mdi.jsx';
import Page from '../Components/Page.jsx';
import Classroom from '../Models/Classroom';

export default class Dashboard extends Page {
  title = __('Dashboard');

  classrooms: Collection<Classroom> = collect();

  async oninit(vnode) {
    this.classrooms = collect(await Classroom.all()).groupBy('id');
    if (this.classrooms.isNotEmpty()) {
      m.redraw();
    }
  }

  view(vnode) {
    const fabAttributes = {};
    if (!md.mobile()) {
      fabAttributes.extended = true;
      fabAttributes.label = __('Add classroom');
    }

    return (
      <>
        <h3>{__('Classrooms')}</h3>
        <mwc-layout-grid>
          <mwc-layout-grid inner>
            {this.classrooms()}
          </mwc-layout-grid>
        </mwc-layout-grid>
        <mwc-fab className="sticky" {...fabAttributes}>
          <Mdi icon="plus" slot="icon"/>
        </mwc-fab>
      </>
    );
  }

  async classrooms() {
    return this.classrooms.map((classroom: Classroom) => {
      const attributes = {
        background: classroom.image,
        title: classroom.name
      };
      return (
        <mwc-card outlined key={classroom.code} {...attributes} onclick={this.goToClassroom.bind(this)}>
          <h4>{classroom.name}</h4>
        </mwc-card>
      );
    });
  }


  goToClassroom(event: Event) {
    const code = $(event.target).closest('.mdc-layout-grid__cell').attr('key');
    Inertia.visit(window.route('classrooms.show', {classroom: code}));
  }
}
