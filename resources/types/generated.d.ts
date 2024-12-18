declare namespace App.Data {
export type AccountData = {
email: string;
first_name: string;
last_name: string | null;
updated_at: string;
};
}
declare namespace App.Data.Auth {
export type AuthData = {
authUser: App.Data.AccountData;
accessToken: string;
};
export type LoginData = {
email: string;
password: string;
remember?: boolean;
};
}
