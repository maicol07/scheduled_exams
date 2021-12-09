import {Model as BaseModel, PluralResponse} from 'coloquent';
import {snakeCase} from 'lodash-es';
import pluralize from 'pluralize';

/**
 * @property {number} id
 */
export default class Model extends BaseModel {
  /**
   * Just an alias to the get() method
   */
  static all(): Promise<PluralResponse<InstanceType<Model>>> {
    return this.get();
  }

  setAttributes(attributes: { [string]: any }): void {
    for (const [attribute, value] of Object.entries(attributes)) {
      this[attribute] = value;
    }
  }

  getJsonApiBaseUrl(): string {
    return '/api';
  }

  getJsonApiType(): string {
    return (super.getJsonApiType() ?? pluralize(snakeCase(this.constructor.name)));
  }
}
