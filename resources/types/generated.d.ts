declare namespace App.Data {
    export type MediaData = {
        name: string;
        size: number;
        url: string;
    };
}
declare namespace App.Data.Admin {
    export type AccountData = {
        email: string;
        first_name: string;
        last_name: string | null;
        updated_at: string;
    };
}
declare namespace App.Data.Admin.Admins {
    export type AdminData = {
        id: number;
        first_name: string;
        last_name: string | null;
        role: App.Enums.AdminRole;
        email: string;
        phone: string | null;
    };
    export type ListAdminData = {
        id: number;
        first_name: string;
        last_name: string;
        role: App.Enums.AdminRole;
        email: string;
        phone: string | null;
        updated_at: string;
    };
    export type StoreAdminData = {
        first_name: string;
        last_name: string;
        email: string;
        role: App.Enums.AdminRole;
        phone?: string | null;
        password: string;
    };
    export type UpdateAdminData = {
        first_name?: string;
        last_name?: string | null;
        email?: string;
        role?: App.Enums.AdminRole;
        phone?: string | null;
        password?: string;
    };
}
declare namespace App.Data.Admin.Auth {
    export type AuthData = {
        authUser: App.Data.Admin.AccountData;
        accessToken: string;
    };
    export type LoginData = {
        email: string;
        password: string;
        remember?: boolean;
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
declare namespace App.Data.Admin.Groups {
    export type GroupData = {
        id: number;
        name: string;
        plan_id: number | null;
        users_ids: Array<number>;
    };
    export type ListGroupData = {
        id: number;
        name: string;
        plan: App.Data.Admin.Plans.SelectPlanData | null;
        updated_at: string;
    };
    export type SelectGroupData = {
        id: number;
        name: string;
    };
    export type StoreGroupData = {
        name: string;
        plan_id?: number | null;
        users_ids?: Array<number> | null;
    };
    export type UpdateGroupData = {
        name?: string;
        plan_id?: number | null;
        users_ids?: Array<number> | null;
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
        groups?: Array<App.Data.Admin.Intervals.IntervalPriceGroupData>;
    };
    export type IntervalPriceGroupData = {
        group_id: number;
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
declare namespace App.Data.Admin.Plans {
    export type ListPlanData = {
        id: number;
        name: string;
        type: string;
        price: number;
        active: boolean;
        updated_at: string;
    };
    export type PlanData = {
        id: number;
        name: string;
        type: string;
        reservations_per_week: number;
        price: number;
        active: boolean;
    };
    export type SelectPlanData = {
        id: number;
        name: string;
    };
    export type StorePlanData = {
        name: string;
        type: string;
        reservations_per_week: number;
        price: number;
        active?: boolean;
    };
    export type UpdatePlanData = {
        name?: string;
        type?: string;
        reservations_per_week?: number;
        price?: number;
        active?: boolean;
    };
}
declare namespace App.Data.Admin.Users {
    export type ListUserData = {
        id: number;
        first_name: string;
        last_name: string | null;
        email: string;
        balance: number;
        discount_on_everything: number;
        birthday: string | null;
        phone: string | null;
        is_company: boolean;
        company_name: string | null;
        updated_at: string;
    };
    export type SelectUserData = {
        id: number;
        full_name: string;
    };
    export type StoreUserData = {
        first_name: string;
        last_name?: string | null;
        email: string;
        balance?: number;
        discount_on_everything?: number;
        birthday?: string | null;
        phone?: string | null;
        is_company?: boolean;
        company_name?: string | null;
        company_code?: string | null;
        company_vat_code?: string | null;
        company_address?: string | null;
        company_phone?: string | null;
        password: string;
    };
    export type UpdateUserData = {
        first_name?: string;
        last_name?: string | null;
        email?: string;
        balance?: number;
        discount_on_everything?: number;
        birthday?: string | null;
        phone?: string | null;
        is_company?: boolean;
        company_name?: string | null;
        company_code?: string | null;
        company_vat_code?: string | null;
        company_address?: string | null;
        company_phone?: string | null;
        password?: string;
    };
    export type UserData = {
        id: number;
        first_name: string;
        last_name: string | null;
        email: string;
        balance: number;
        discount_on_everything: number;
        birthday: string | null;
        phone: string | null;
        is_company: boolean;
        company_name: string | null;
        company_code: string | null;
        company_vat_code: string | null;
        company_address: string | null;
        company_phone: string | null;
    };
}
declare namespace App.Data.User {
    export type AccountData = {
        email: string;
        first_name: string;
        last_name: string | null;
        birthday: string | null;
        phone: string | null;
        balance: number;
        cancel_before: number;
        is_company: boolean;
        company_name: string | null;
        company_code: string | null;
        company_vat_code: string | null;
        company_address: string | null;
        company_phone: string | null;
    };
    export type ContactUsData = {
        name: string;
        email: string;
        phone: string;
        message: string;
    };
}
declare namespace App.Data.User.Account {
    export type ChangeAccountPasswordData = {
        old_password: string;
        password: string;
    };
    export type TopUpAccountBalanceData = {
        amount: number;
    };
    export type UpdateAccountData = {
        first_name?: string;
        last_name?: string;
        email?: string;
        birthday?: string;
        phone?: string;
        is_company?: boolean;
        company_name?: string | null;
        company_code?: string | null;
        company_vat_code?: string | null;
        company_address?: string | null;
        company_phone?: string | null;
    };
}
declare namespace App.Data.User.Auth {
    export type AuthData = {
        authUser: App.Data.User.AccountData;
        accessToken: string;
    };
    export type LoginData = {
        email: string;
        password: string;
        remember?: boolean;
    };
    export type PasswordRecoveryData = {
        email: string;
    };
    export type PasswordResetData = {
        token: string;
        email: string;
        password: string;
    };
    export type RegisterData = {
        email: string;
        first_name: string;
        last_name: string;
        birthday: string;
        phone: string;
        password: string;
    };
}
declare namespace App.Data.User.Courts {
    export type CourtData = {
        id: number;
        name: string;
        description: string | null;
        type: App.Enums.CourtType;
        logo: App.Data.MediaData | null;
    };
    export type CourtSlotData = {
        court_id: number;
        date: string;
        day: App.Enums.Day;
        start_time: string;
        end_time: string;
        price: number;
    };
    export type CourtTimesData = {
        date: string;
    };
    export type ListCourtData = {
        id: number;
        name: string;
        description: string | null;
        fast_slots: Array<App.Data.User.Courts.CourtSlotData>;
        type: App.Enums.CourtType;
        logo: App.Data.MediaData | null;
    };
}
declare namespace App.Data.User.Payments {
    export type ListPaymentData = {
        id: number;
        paymentable_type: string | null;
        paid_amount_from_balance: number;
        paid_amount: number;
        price_with_vat: number;
        paid_at: string;
    };
    export type PaymentData = {
        status: string;
        type: string | null;
        email: string;
        balance: number | null;
    };
}
declare namespace App.Data.User.Plans {
    export type PlanData = {
        id: number;
        name: string;
        type: string;
        reservations_per_week: number;
        price: number;
    };
}
declare namespace App.Data.User.ReservationTimes {
    export type IndexReservationTimeData = {
        type: string;
    };
    export type ReservationSlotData = {
        slot_start: string;
        slot_end: string;
        price: number;
        is_refunded: boolean;
    };
    export type ReservationTimeData = {
        id: number;
        courtName: string;
        date: string;
        start_time: string;
        end_time: string;
        price: number;
        refunded_amount: number;
        is_past: number;
        cancelled_at: string | null;
        slots: Array<App.Data.User.ReservationTimes.ReservationSlotData>;
    };
}
declare namespace App.Data.User.Reservations {
    export type ReservationSlotData = {
        court_id: number;
        date: string;
        start_time: string;
        end_time: string;
    };
    export type StoreReservationData = {
        guest_email: string | null;
        guest_first_name: string | null;
        guest_last_name: string | null;
        guest_phone: string | null;
        user_id: number | null;
        slots: Array<App.Data.User.Reservations.ReservationSlotData>;
    };
}
declare namespace App.Data.User.Subscriptions {
    export type SubscriptionData = {
        started_at: string;
        expired_at: string;
        cancelled_at: string | null;
        plan: App.Data.User.Plans.PlanData;
        reservations_per_week: number;
    };
}
declare namespace App.Enums {
    export type AdminRole = "superAdmin" | "employee";
    export type CourtType = "indoor" | "outdoor";
    export type Day = "mon" | "tue" | "wed" | "thu" | "fri" | "sat" | "sun";
    export type FeatureType = "reservations_per_week";
    export type PaymentStatus = "pending" | "paid" | "cancelled" | "expired";
}
