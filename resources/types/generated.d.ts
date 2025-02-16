declare namespace App.Data {
    export type AccountData = {
        email: string;
        first_name: string;
        last_name: string | null;
        updated_at: string;
    };
    export type MediaData = {
        name: string;
        size: number;
        url: string;
    };
}
declare namespace App.Data.Admin.Courts {
    export type CourtData = {
        id: number;
        name: string;
        inside_name: string;
        description: string | null;
        active: boolean;
        type: App.Enums.CourtType;
        logo: App.Data.MediaData | null;
        intervals_ids: Array<number>;
    };
    export type ListCourtData = {
        id: number;
        name: string;
        inside_name: string;
        active: boolean;
        type: App.Enums.CourtType;
        logo: App.Data.MediaData | null;
        updated_at: string;
    };
    export type StoreCourtData = {
        name: string;
        inside_name: string | null;
        description: string | null;
        type: App.Enums.CourtType;
        active?: boolean;
        logoFile?: any;
        intervals_ids?: Array<number> | null;
    };
    export type UpdateCourtData = {
        name?: string;
        inside_name?: string | null;
        description?: string | null;
        type?: App.Enums.CourtType;
        active?: boolean;
        logoFile?: any;
        deleteLogo?: boolean;
        intervals_ids?: Array<number> | null;
    };
}
declare namespace App.Data.Admin.Intervals {
    export type IntervalData = {
        id: number;
        name: string;
        inside_name: string | null;
        date_from: string;
        date_to: string;
        prices: Array<App.Data.Admin.Intervals.IntervalPriceData>;
    };
    export type IntervalPriceData = {
        day: App.Enums.Day;
        start_time: string;
        end_time: string;
        price: number;
    };
    export type ListIntervalData = {
        id: number;
        name: string;
        inside_name: string | null;
        date_from: string;
        date_to: string;
        updated_at: string;
    };
    export type SelectIntervalData = {
        id: number;
        name: string;
        inside_name: string | null;
        date_from: string;
        date_to: string;
    };
    export type StoreIntervalData = {
        name: string;
        inside_name: string | null;
        date_from: string;
        date_to: string;
        prices?: Array<App.Data.Admin.Intervals.IntervalPriceData>;
    };
    export type UpdateIntervalData = {
        name?: string;
        inside_name?: string | null;
        date_from?: string;
        date_to?: string;
        prices?: Array<App.Data.Admin.Intervals.IntervalPriceData>;
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
declare namespace App.Enums {
    export type CourtType = "indoor" | "outdoor";
    export type Day = "mon" | "tue" | "wed" | "thu" | "fri" | "sat" | "sun";
}
