export const UserFunctions = {
    getConnectedUser: function () {
        return JSON.parse(sessionStorage.getItem('user'));
    }
}