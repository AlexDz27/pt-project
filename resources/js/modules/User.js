export class User {
  static signIn(token) {
    this.storeToken(token);
  }

  static signOut() {
    this.deleteToken();
  }

  static signedIn() {
    return Boolean(localStorage.getItem('token'));
  }

  static initializeIfHasToken() {
    const currentToken = localStorage.getItem('token');

    this.signIn(currentToken);
  }

  static storeToken(token) {
    localStorage.setItem('token', token);
  }

  static deleteToken() {
    localStorage.removeItem('token');
  }
}
