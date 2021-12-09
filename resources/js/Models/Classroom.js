import Model from './Model';

export default class Classroom extends Model {
  get name(): string {
    return this.getAttribute('name');
  }

  set name(name: string): void {
    this.setAttribute('name', name);
  }

  get description(): string {
    return this.getAttribute('description');
  }

  set description(description: string): void {
    this.setAttribute('description', description);
  }

  get image(): string {
    return this.getAttribute('image');
  }

  set image(image: string): void {
    this.setAttribute('image', image);
  }

  get code(): string {
    return this.getAttribute('code');
  }

  set code(code: string): void {
    this.setAttribute('code', code);
  }
}
