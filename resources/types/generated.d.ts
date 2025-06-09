declare namespace App.Data.Admin.Admins {
    export type AdminData = {
        id: number;
        first_name: string;
        last_name: string | null;
        role: App.Enums.AdminRole;
        email: string;
        phone: string | null;
    };
    export type AdminListData = {
        id: number;
        first_name: string;
        last_name: string;
        role: App.Enums.AdminRole;
        email: string;
        phone: string | null;
        updated_at: string;
    };
    export type AdminStoreData = {
        first_name: string;
        last_name: string;
        email: string;
        role: App.Enums.AdminRole;
        phone?: string | null;
        password: string;
    };
    export type AdminUpdateData = {
        first_name?: string;
        last_name?: string | null;
        email?: string;
        role?: App.Enums.AdminRole;
        phone?: string | null;
        password?: string;
    };
}
declare namespace App.Data.Admin.Auth {
    export type AccountData = {
        email: string;
        first_name: string;
        last_name: string | null;
        updated_at: string;
    };
    export type AuthData = {
        authUser: App.Data.Admin.Auth.AccountData;
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
        logo: App.Data.Core.Media.MediaData | null;
        intervals_ids: Array<number>;
    };
    export type CourtListData = {
        id: number;
        name: string;
        inside_name: string;
        active: boolean;
        type: App.Enums.CourtType;
        logo: App.Data.Core.Media.MediaData | null;
        updated_at: string;
    };
    export type CourtSelectOptionData = {
        id: number;
        name: string;
        type: App.Enums.CourtType;
    };
    export type CourtStoreData = {
        name: string;
        inside_name: string | null;
        description: string | null;
        type: App.Enums.CourtType;
        active?: boolean;
        logoFile?: any;
        intervals_ids?: Array<number> | null;
    };
    export type CourtUpdateData = {
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
declare namespace App.Data.Admin.Dashboard {
    export type IncomeFilterData = {
        date_from: string;
        date_to?: string;
    };
}
declare namespace App.Data.Admin.Downtimes {
    export type DowntimeData = {
        id: number;
        court_id: number;
        date_from: string;
        date_to: string;
        start_time: string;
        end_time: string;
        comment: string | null;
    };
    export type DowntimeInputData = {
        court_id: number;
        date_from: string;
        date_to: string;
        start_time: string;
        end_time: string;
        comment?: string | null;
    };
    export type DowntimeListData = {
        id: number;
        court: App.Data.Admin.Courts.CourtSelectOptionData;
        date_from: string;
        date_to: string;
        start_time: string;
        end_time: string;
        comment: string | null;
        updated_at: string;
    };
}
declare namespace App.Data.Admin.Groups {
    export type GroupData = {
        id: number;
        name: string;
        plan_id: number | null;
        users_ids: Array<number>;
    };
    export type GroupListData = {
        id: number;
        name: string;
        plan: App.Data.Admin.Plans.PlanSelectOptionData | null;
        updated_at: string;
    };
    export type GroupSelectOptionData = {
        id: number;
        name: string;
    };
    export type GroupStoreData = {
        name: string;
        plan_id?: number | null;
        users_ids?: Array<number> | null;
    };
    export type GroupUpdateData = {
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
    export type IntervalListData = {
        id: number;
        name: string;
        inside_name: string | null;
        date_from: string;
        date_to: string;
        updated_at: string;
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
    export type IntervalSelectOptionData = {
        id: number;
        name: string;
        inside_name: string | null;
        date_from: string;
        date_to: string;
    };
    export type IntervalStoreData = {
        name: string;
        inside_name: string | null;
        date_from: string;
        date_to: string;
        prices?: Array<App.Data.Admin.Intervals.IntervalPriceData>;
    };
    export type IntervalUpdateData = {
        name?: string;
        inside_name?: string | null;
        date_from?: string;
        date_to?: string;
        prices?: Array<App.Data.Admin.Intervals.IntervalPriceData>;
    };
}
declare namespace App.Data.Admin.Plans {
    export type PlanData = {
        id: number;
        name: string;
        type: string;
        cancel_before: number;
        price: number;
        active: boolean;
    };
    export type PlanListData = {
        id: number;
        name: string;
        type: string;
        price: number;
        active: boolean;
        updated_at: string;
    };
    export type PlanSelectOptionData = {
        id: number;
        name: string;
    };
    export type PlanStoreData = {
        name: string;
        type: string;
        cancel_before: number;
        price: number;
        active?: boolean;
    };
    export type PlanUpdateData = {
        name?: string;
        type?: string;
        cancel_before?: number;
        price?: number;
        active?: boolean;
    };
}
declare namespace App.Data.Admin.Reservations {
    export type MultiReservationResultData = {
        free_slots: any;
        blocked_slots: any;
    };
    export type MultiReservationStoreData = {
        user_id: number;
        court_type: App.Enums.CourtType;
        court_id?: number | null;
        date_from: string;
        date_to: string;
        force_create: boolean;
        time_blocks: Array<App.Data.Admin.Reservations.TimeBlockStoreData>;
    };
    export type ReservationCalendarData = {
        start_time: string;
        end_time: string;
        court: App.Data.Admin.Courts.CourtSelectOptionData;
        owner_type: string;
        owner: App.Data.Core.Owners.OwnerData;
        price_with_vat: number;
        is_paid: boolean;
    };
    export type ReservationFilterData = {
        courts_ids?: Array<number>;
        date_from: string;
        date_to: string;
        court_type?: App.Enums.CourtType;
    };
    export type ReservationListData = {
        id: number;
        start_time: string;
        end_time: string;
        court: App.Data.Admin.Courts.CourtSelectOptionData;
        owner_type: string;
        owner: App.Data.Core.Owners.OwnerData;
        price_with_vat: number;
        is_paid: boolean;
        paid_at: string | null;
        canceled_at: string | null;
        updated_at: string;
    };
    export type TimeBlockStoreData = {
        day: App.Enums.Day;
        start_time: string;
        end_time: string;
    };
}
declare namespace App.Data.Admin.Subscriptions {
    export type SubscriptionListData = {
        id: number;
        plan: App.Data.Admin.Plans.PlanSelectOptionData | null;
        subscriber: App.Data.Admin.Users.UserSelectOptionData | null;
        started_at: string;
        expired_at: string;
        is_overdue: boolean;
    };
}
declare namespace App.Data.Admin.Users {
    export type UserData = {
        id: number;
        first_name: string;
        last_name: string | null;
        email: string;
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
    export type UserListData = {
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
    export type UserSelectOptionData = {
        id: number;
        full_name: string;
    };
    export type UserStoreData = {
        first_name: string;
        last_name?: string | null;
        email: string;
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
    export type UserUpdateData = {
        first_name?: string;
        last_name?: string | null;
        email?: string;
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
}
declare namespace App.Data.Core.Media {
    export type MediaData = {
        name: string;
        size: number;
        url: string;
    };
}
declare namespace App.Data.Core.Owners {
    export type OwnerData = {
        full_name: string;
        email: string;
        phone: string;
    };
}
declare namespace App.Data.Core.Pricing {
    export type PriceDetailsData = {
        price: number;
        discount: number;
        vat: number;
        price_with_vat: number;
    };
}
declare namespace App.Data.User {
    export type ContactUsData = {
        name: string;
        email: string;
        phone: string;
        message: string;
    };
}
declare namespace App.Data.User.Account {
    export type AccountBalanceTopUpData = {
        amount: number;
    };
    export type AccountData = {
        email: string;
        first_name: string;
        last_name: string | null;
        birthday: string | null;
        phone: string | null;
        balance: number;
        discount_on_everything: number;
        cancel_before: number;
        is_company: boolean;
        company_name: string | null;
        company_code: string | null;
        company_vat_code: string | null;
        company_address: string | null;
        company_phone: string | null;
    };
    export type AccountPasswordChangeData = {
        old_password: string;
        password: string;
    };
    export type AccountUpdateData = {
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
        authUser: App.Data.User.Account.AccountData;
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
    export type SocialData = {
        accessToken: string;
        social: App.Enums.Social;
    };
}
declare namespace App.Data.User.Courts {
    export type CourtData = {
        id: number;
        name: string;
        description: string | null;
        type: App.Enums.CourtType;
        logo: App.Data.Core.Media.MediaData | null;
    };
    export type CourtFilterData = {
        date: string;
    };
    export type CourtListData = {
        id: number;
        name: string;
        description: string | null;
        slots: Array<App.Data.User.Courts.CourtSlotData>;
        type: App.Enums.CourtType;
        logo: App.Data.Core.Media.MediaData | null;
    };
    export type CourtSlotData = {
        court_id: number;
        date: string;
        day: App.Enums.Day;
        start_time: string;
        end_time: string;
        price: number;
        original_price: number;
    };
}
declare namespace App.Data.User.Guests {
    export type GuestStoreData = {
        email: string;
        first_name: string;
        last_name: string;
        phone: string;
    };
}
declare namespace App.Data.User.Payments {
    export type PaymentData = {
        status: App.Enums.PaymentStatus;
        type: string | null;
        owner: App.Data.Core.Owners.OwnerData;
        balance: number | null;
    };
    export type PaymentListData = {
        id: number;
        paymentable_type: string | null;
        paid_amount_from_balance: number;
        paid_amount: number;
        price_with_vat: number;
        paid_at: string;
    };
}
declare namespace App.Data.User.Plans {
    export type PlanData = {
        id: number;
        name: string;
        type: string;
        price: number;
        cancel_before: number;
    };
}
declare namespace App.Data.User.Reservations {
    export type ReservationData = {
        id: number;
        courtName: string;
        date: string;
        start_time: string;
        end_time: string;
        price_with_vat: number;
        refunded_amount: number;
        is_paid: boolean;
        is_past: number;
        cancelled_at: string | null;
        slots: Array<App.Data.User.Reservations.ReservationSlotStoreData>;
    };
    export type ReservationFilterData = {
        type: string;
        date: string | null;
    };
    export type ReservationPayData = {
        reservations_ids: Array<any>;
    };
    export type ReservationSlotData = {
        slot_start: string;
        slot_end: string;
        price_with_vat: number;
        is_refunded: boolean;
    };
    export type ReservationSlotStoreData = {
        court_id: number;
        date: string;
        start_time: string;
        end_time: string;
    };
    export type ReservationStoreData = {
        guest: App.Data.User.Guests.GuestStoreData | null;
        slots: Array<App.Data.User.Reservations.ReservationSlotStoreData>;
    };
}
declare namespace App.Data.User.Subscriptions {
    export type SubscriptionData = {
        started_at: string;
        expired_at: string;
        cancelled_at: string | null;
        plan: App.Data.User.Plans.PlanData;
    };
}
declare namespace App.Enums {
    export type AdminRole = "superAdmin" | "employee";
    export type CourtType = "tennis" | "table_tennis";
    export type Day = "mon" | "tue" | "wed" | "thu" | "fri" | "sat" | "sun";
    export type PaymentStatus = "pending" | "paid" | "cancelled" | "expired";
    export type Social = "google";
}
