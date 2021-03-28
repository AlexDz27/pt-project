export class ApiCaller {
  static async getLocationById(id, token) {
    const response = await fetch(window.API_URL + '/locations/' + id, {
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });

    return response;
  }

  static async getUserById(id) {
    const response = await fetch(window.API_URL + '/user/' + id);

    return response;
  }

  static async signUp(credentials) {
    const response = await fetch(window.API_URL + '/auth/sign-up', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(credentials)
    });

    return response;
  }

  static async signIn(credentials) {
    const response = await fetch(window.API_URL + '/auth/sign-in', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(credentials)
    });

    return response;
  }
}
