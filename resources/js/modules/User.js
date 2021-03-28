export class User {
  static signIn(token, profile) {
    this.storeToken(token);
    this.storeProfile(profile);
  }

  static signOut() {
    this.clearToken();
    this.clearProfile();
  }

  static signedIn() {
    return Boolean(localStorage.getItem('token'));
  }

  static initializeIfHasToken() {
    const currentToken = localStorage.getItem('token');

    this.signIn(currentToken);
  }

  static storeProfile(profile) {
    localStorage.setItem('profile', JSON.stringify(profile));
  }

  static storeToken(token) {
    localStorage.setItem('token', token);
  }

  static clearProfile() {
    localStorage.removeItem('profile');
  }

  static clearToken() {
    localStorage.removeItem('token');
  }
}
