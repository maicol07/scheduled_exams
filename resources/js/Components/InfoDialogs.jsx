// eslint-disable-next-line no-secrets/no-secrets
// noinspection JSVoidFunctionReturnValueUsed

import '@material/mwc-circular-progress';
import '@material/mwc-dialog';
import 'animate.css';

import {type Cash} from 'cash-dom/dist/cash';
import {sync as render} from 'mithril-node-render';
import redaxios, {type Response} from 'redaxios';

import Component from './Component.jsx';
import Mdi from './Mdi.jsx';

export class AppInfoDialog extends Component {
  view(vnode) {
    return (
      <mwc-dialog trigger="app-info-btn" heading={__('About Scheduled Exams :version', {version: $('#app-info-container').data('version')})}>
        <p>
          {__('Scheduled Exams is a closed source app developed by :author, ', {
            author: <a href="https://maicol07.it">Maicol Battistini (maicol07)</a>
          })}
        </p>
        <p>{__('Open source libraries used:')}</p>
        <div id="libraries" style="margin-top: 8px;">
          <div className="loading" style="display: flex; justify-content: center;">
            <mwc-circular-progress indeterminate/>
            <span style="margin-top: 2px; margin-left: 16px;">{__('Loading…')}</span>
          </div>
        </div>

        {/* eslint-disable-next-line no-secrets/no-secrets */}
        <a href="https://changelog.maicol07.it/?categories=cat_a6Iz9EjFWrfgf&view=complete" target="_blank"
           slot="secondaryAction">
          <mwc-button label={__('Release notes')}/>
        </a>
        <a href="https://community.maicol07.it" target="_blank" slot="secondaryAction">
          <mwc-button label={__('Community')}/>
        </a>
        <mwc-button label={__('OK')} slot="primaryAction" dialogAction="ok"/>
      </mwc-dialog>
    );
  }

  oncreate(vnode) {
    $(vnode.dom).on('opening', async () => {
      const dialog = $(vnode.dom);
      dialog.find('mwc-circular-progress').attr('density', -6);

      // Close user info menu
      $(`#${dialog.attr('trigger')}`).closest('mwc-menu').get(0).close();

      const container = dialog.children('#libraries');
      if (container.has('.loading')) {
        const list = container.prepend('<mwc-list id="libraries"></mwc-list>').children().first();

        let index = 0;
        let loaded = false;
        const failed = false;
        do {
          // eslint-disable-next-line no-await-in-loop
          const response: Response = await redaxios(window.route('app.libraries', {
            offset: index * 5,
            length: 5
          }));

          if (response.ok) {
            const libraries = response.data;
            if (!libraries || libraries.length === 0) {
              loaded = true;
            }

            this.pushLibraries(list, libraries);
          }

          index += 1;
        } while (!loaded);

        if (failed) {
          list.append(
            render(
              <>
                <Mdi icon="alert-circle-outline" style="margin-right: 8px;"/>
                <span>{__("Can't load the libraries list. Please try again later.")}</span>
              </>
            )
          );
        }

        container.before(list).remove();
      }
    });
  }

  pushLibraries(list: Cash, libraries: {
    [string]: {
      name: string,
      description: string,
      homepage: string,
      keywords: [string],
      support?: {
        source: string
      }
    }
  }) {
    const html = [];
    for (const [name, library] of Object.entries(libraries)) {
      let link = library.homepage;

      if (link && library.support && library.support.source) {
        link = library.support.source;
      }

      html.push(
        <mwc-list-item twoline hasMeta>
          <span>{name}</span>
          <span slot="secondary">{library.description}</span>
          {link && <a href={link} target="_blank" slot="meta" style="display: flex; align-items: center;">
            <mwc-icon-button>
              <Mdi icon="open-in-new"/>
            </mwc-icon-button>
          </a>}
        </mwc-list-item>
      );
    }

    list.append(render(html));
  }
}
