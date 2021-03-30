export class ApiCaller {
  static async searchLocations(params, token) {
    const urlParams = new URLSearchParams(params).toString();

    console.log(window.API_URL + '/search?' + urlParams)

    const response = await fetch(window.API_URL + '/search?' + urlParams, {
      headers: {
        'Authorization': 'Bearer ' + token
      }
    });
    const result = await response.json();

    const locationsSearch = result.locations;

    return locationsSearch;
  }

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

  static async signInViaGoogle(googleUserSignInData) {
    const response = await fetch(window.API_URL + '/auth/oauth/google', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(googleUserSignInData)
    });

    return response;
  }

  static async resetPassword(token, form) {
    const response = await fetch(window.API_URL + '/auth/reset-password?token=' + token, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(form)
    });

    return response;
  }

  static async validateResetPasswordToken(token) {
    const response = await fetch(window.API_URL + '/auth/reset-password?token=' + token);

    return response;
  }

  static async forgotPassword(form) {
    const response = await fetch(window.API_URL + '/auth/forgot-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(form)
    });

    return response;
  }
}
