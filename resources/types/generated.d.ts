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
    export type AdminSelectOptionData = {
        id: number;
        full_name: string;
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
declare namespace App.Data.Admin.Availability {
    export type AvailabilityMainStatsData = {
        yesterday: App.Data.Admin.Availability.AvailabilityStatsWithCourtTypesData;
        today: App.Data.Admin.Availability.AvailabilityStatsWithCourtTypesData;
        tomorrow: App.Data.Admin.Availability.AvailabilityStatsWithCourtTypesData;
        months: App.Data.Admin.Availability.AvailabilityMonthsStatsData;
    };
    export type AvailabilityMonthsStatsData = {
        labels: Array<string>;
        data: Array<App.Data.Admin.Availability.AvailabilityStatsWithCourtTypesData>;
    };
    export type AvailabilityStatsData = {
        total: number;
        reserved: number;
        reserved_pct: number;
        blocked: number;
        blocked_pct: number;
        free: number;
        free_pct: number;
        occupied: number;
        occupied_pct: number;
    };
    export type AvailabilityStatsWithCourtTypesData = {
        overall: App.Data.Admin.Availability.AvailabilityStatsData;
        by_court_type: Array<App.Data.Admin.Availability.AvailabilityStatsData> | null;
    };
    export type StatsFilterData = {
        court_type_id?: number;
        date_from: string;
        date_to?: string;
        time_from?: string;
        time_to?: string;
    };
}
declare namespace App.Data.Admin.Courts {
    export type CourtData = {
        id: number;
        name: string;
        inside_name: string;
        description: string | null;
        active: boolean;
        court_type_id: number;
        logo: App.Data.Core.Media.MediaData | null;
        intervals_ids: Array<number>;
        litecom_zones_ids: Array<number>;
    };
    export type CourtListData = {
        id: number;
        name: string;
        inside_name: string;
        active: boolean;
        courtType: App.Data.Core.CourtTypes.CourtTypeSelectOptionData;
        logo: App.Data.Core.Media.MediaData | null;
        updated_at: string;
    };
    export type CourtSelectOptionData = {
        id: number;
        name: string;
        court_type_id: number;
    };
    export type CourtStoreData = {
        name: string;
        inside_name: string | null;
        description: string | null;
        court_type_id: number;
        active?: boolean;
        logoFile?: any;
        intervals_ids?: Array<number> | null;
        litecom_zones_ids?: Array<number> | null;
    };
    export type CourtUpdateData = {
        name?: string;
        inside_name?: string | null;
        description?: string | null;
        court_type_id?: number;
        active?: boolean;
        logoFile?: any;
        deleteLogo?: boolean;
        intervals_ids?: Array<number> | null;
        litecom_zones_ids?: Array<number> | null;
    };
}
declare namespace App.Data.Admin.Dashboard {
    export type IncomeFilterData = {
        date_from: string;
        date_to?: string;
    };
}
declare namespace App.Data.Admin.DiscountCodes {
    export type DiscountCodeData = {
        id: number;
        name: string;
        code: string;
        is_active: boolean;
        type: App.Enums.DiscountCodeType;
        value: number;
        usage_limit: number | null;
        date_from: string | null;
        date_to: string | null;
    };
    export type DiscountCodeListData = {
        id: number;
        name: string;
        code: string;
        is_active: boolean;
        type: App.Enums.DiscountCodeType;
        value: number;
        usage_limit: number | null;
        used: number;
        date_from: string | null;
        date_to: string | null;
        updated_at: string;
    };
    export type DiscountCodeStoreData = {
        name: string;
        code: string;
        type: App.Enums.DiscountCodeType;
        value: number;
        usage_limit?: number | null;
        date_from?: string | null;
        date_to?: string | null;
        is_active?: boolean;
    };
    export type DiscountCodeUpdateData = {
        name: string;
        code: string;
        type: App.Enums.DiscountCodeType;
        value: number;
        usage_limit?: number | null;
        date_from?: string | null;
        date_to?: string | null;
        is_active?: boolean;
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
declare namespace App.Data.Admin.Forms {
    export type BeginnerFormListData = {
        email: string;
        first_name: string;
        last_name: string;
        phone: string;
        age: number;
        groups: string;
        marketing_consent: boolean;
        updated_at: string;
    };
}
declare namespace App.Data.Admin.FutureMembers {
    export type FutureMemberListData = {
        email: string;
        first_name: string;
        last_name: string;
        phone: string | null;
        services: string;
        days: string | null;
        times: string | null;
        plan: string | null;
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
declare namespace App.Data.Admin.Invoices {
    export type InvoiceExportData = {
        date_from: string;
        date_to: string;
    };
    export type InvoiceListData = {
        id: number;
        number: number;
        date: string;
        owner_type: string;
        owner: App.Data.Core.Owners.OwnerData;
        price: number;
        vat: number;
        price_with_vat: number;
        updated_at: string;
    };
}
declare namespace App.Data.Admin.LitecomZones {
    export type LitecomZoneData = {
        id: number;
        name: string;
        auto_scene: string;
        auto_turn_on_before: number;
        auto_turn_off_after: number;
    };
    export type LitecomZoneListData = {
        id: number;
        name: string;
        auto_scene: number;
        auto_turn_on_before: number;
        auto_turn_off_after: number;
        active_scene: number;
        manual_override_until: string | null;
        updated_at: string;
    };
    export type LitecomZoneOnData = {
        scene: number;
        manual_override_until: string | null;
    };
    export type LitecomZoneSelectOptionData = {
        id: number;
        name: string;
    };
    export type LitecomZoneUpdateData = {
        name?: string;
        auto_scene?: number;
        auto_turn_on_before?: number;
        auto_turn_off_after?: number;
    };
}
declare namespace App.Data.Admin.Payments {
    export type PaymentListData = {
        id: number;
        owner_type: string;
        owner: App.Data.Core.Owners.OwnerData;
        paymentable_type: string | null;
        price: number;
        discount: number;
        vat: number;
        price_with_vat: number;
        paid_amount_from_balance: number;
        paid_amount: number;
        paid_at: string;
        updated_at: string;
    };
}
declare namespace App.Data.Admin.PlanCourtTypeRules {
    export type PlanCourtTypeRuleData = {
        id: number;
        courtType: App.Data.Core.CourtTypes.CourtTypeSelectOptionData;
        max_days_in_advance: number;
        cancel_hours_before: number;
        slots: Array<App.Data.Admin.PlanCourtTypeRules.PlanCourtTypeRuleSlotData>;
    };
    export type PlanCourtTypeRuleListData = {
        id: number;
        courtType: App.Data.Core.CourtTypes.CourtTypeSelectOptionData;
        max_days_in_advance: number;
        cancel_hours_before: number;
        updated_at: string;
    };
    export type PlanCourtTypeRuleSlotData = {
        day: App.Enums.Day;
        start_time: string;
        end_time: string;
    };
    export type PlanCourtTypeRuleUpdateData = {
        max_days_in_advance?: number;
        cancel_hours_before?: number;
        slots?: Array<App.Data.Admin.PlanCourtTypeRules.PlanCourtTypeRuleSlotData>;
    };
}
declare namespace App.Data.Admin.Plans {
    export type PlanData = {
        id: number;
        name: string;
        type: string;
        is_active: boolean;
        is_popular: boolean;
        is_default: boolean;
        features: Array<App.Data.Admin.Plans.Features.PlanFeatureData>;
        prices: Array<App.Data.Admin.Plans.Prices.PlanPriceData>;
    };
    export type PlanListData = {
        id: number;
        name: string;
        type: string;
        is_active: boolean;
        is_popular: boolean;
        is_default: boolean;
        prices: Array<App.Data.Admin.Plans.Prices.PlanPriceData>;
        updated_at: string;
    };
    export type PlanSelectOptionData = {
        id: number;
        name: string;
    };
    export type PlanStoreData = {
        name: string;
        type: string;
        is_active?: boolean;
        is_popular?: boolean;
        features: Array<App.Data.Admin.Plans.Features.PlanFeatureInsertData>;
        prices: Array<App.Data.Admin.Plans.Prices.PlanPriceInsertData>;
    };
    export type PlanUpdateData = {
        name?: string;
        type?: string;
        price?: number;
        is_active?: boolean;
        is_popular?: boolean;
        features: Array<App.Data.Admin.Plans.Features.PlanFeatureInsertData>;
        prices: Array<App.Data.Admin.Plans.Prices.PlanPriceInsertData>;
    };
}
declare namespace App.Data.Admin.Plans.Features {
    export type PlanFeatureData = {
        id: number;
        label: string;
        subFeatures: Array<App.Data.Admin.Plans.Features.PlanSubFeatureData>;
    };
    export type PlanFeatureInsertData = {
        id: number | null;
        label: string;
        subFeatures?: Array<App.Data.Admin.Plans.Features.PlanSubFeatureInsertData>;
    };
    export type PlanSubFeatureData = {
        id: number;
        label: string;
    };
    export type PlanSubFeatureInsertData = {
        id: number | null;
        label: string;
    };
}
declare namespace App.Data.Admin.Plans.Prices {
    export type PlanPriceData = {
        id: number;
        periodicity_type: string;
        previous_price: number | null;
        price: number;
    };
    export type PlanPriceInsertData = {
        id: number | null;
        periodicity_type: string;
        previous_price: number | null;
        price: number;
    };
}
declare namespace App.Data.Admin.Reports {
    export type UserBalancesExportReportData = {
        date_from: string;
        date_to: string;
        hide_zero_balances: boolean;
        hide_zero_invoices: boolean;
    };
}
declare namespace App.Data.Admin.Reservations {
    export type MultiReservationResultData = {
        free_slots: any;
        blocked_slots: any;
    };
    export type MultiReservationStoreData = {
        user_id: number;
        court_type_id: number;
        court_id?: number | null;
        date_from: string;
        date_to: string;
        force_create: boolean;
        time_blocks: Array<App.Data.Admin.Reservations.TimeBlockStoreData>;
    };
    export type ReservationBulkActionData = {
        ids: Array<number>;
        action: string;
    };
    export type ReservationCalendarData = {
        id: number;
        start_time: string;
        end_time: string;
        court: App.Data.Admin.Courts.CourtSelectOptionData;
        owner_type: string;
        owner: App.Data.Core.Owners.OwnerData;
        price_with_vat: number;
        is_paid: boolean;
        canceled_at: string | null;
    };
    export type ReservationFilterData = {
        courts_ids?: Array<number>;
        date_from: string;
        date_to: string;
        court_type_id?: number;
    };
    export type ReservationListData = {
        id: number;
        start_time: string;
        end_time: string;
        court: App.Data.Admin.Courts.CourtSelectOptionData;
        owner_type: string;
        owner: App.Data.Core.Owners.OwnerData;
        price_with_vat: number;
        refunded_amount: number;
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
        periodicity_type: string;
        subscriber: App.Data.Admin.Users.UserSelectOptionData | null;
        started_at: string;
        expired_at: string;
        is_overdue: boolean;
    };
}
declare namespace App.Data.Admin.Users {
    export type UserBalanceEntryListData = {
        amount: number;
        admin: App.Data.Admin.Admins.AdminSelectOptionData;
        user: App.Data.Admin.Users.UserSelectOptionData | null;
        before_balance: number;
        after_balance: number;
        created_at: string;
    };
    export type UserBalanceEntryStoreData = {
        amount: number;
        reason: string | null;
    };
    export type UserData = {
        id: number;
        first_name: string;
        last_name: string | null;
        email: string;
        overdraft_limit: number;
        discount_on_everything: number;
        birthday: string | null;
        phone: string | null;
        is_company: boolean;
        company_name: string | null;
        company_code: string | null;
        company_vat_code: string | null;
        company_address: string | null;
        company_phone: string | null;
        agreed_newsletter: boolean;
    };
    export type UserListData = {
        id: number;
        first_name: string;
        last_name: string | null;
        email: string;
        balance: number;
        overdraft_limit: number;
        discount_on_everything: number;
        birthday: string | null;
        phone: string | null;
        is_company: boolean;
        company_name: string | null;
        agreed_newsletter: boolean;
        plan: string | null;
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
        overdraft_limit?: number;
        discount_on_everything?: number;
        birthday?: string | null;
        phone?: string | null;
        is_company?: boolean;
        company_name?: string | null;
        company_code?: string | null;
        company_vat_code?: string | null;
        company_address?: string | null;
        company_phone?: string | null;
        agreed_newsletter?: boolean;
        password: string;
    };
    export type UserUpdateData = {
        first_name?: string;
        last_name?: string | null;
        email?: string;
        overdraft_limit?: number;
        discount_on_everything?: number;
        birthday?: string | null;
        phone?: string | null;
        is_company?: boolean;
        company_name?: string | null;
        company_code?: string | null;
        company_vat_code?: string | null;
        company_address?: string | null;
        company_phone?: string | null;
        agreed_newsletter?: boolean;
        password?: string;
    };
}
declare namespace App.Data.Core.CourtTypes {
    export type CourtTypeSelectOptionData = {
        id: number;
        name: string;
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
        phone: string | null;
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
        overdraft_limit: number;
        discount_on_everything: number;
        is_company: boolean;
        company_name: string | null;
        company_code: string | null;
        company_vat_code: string | null;
        company_address: string | null;
        company_phone: string | null;
        has_subscription: boolean;
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
        agreed_terms: boolean;
        agreed_newsletter?: boolean;
    };
    export type SocialData = {
        accessToken: string;
        social: App.Enums.Social;
    };
}
declare namespace App.Data.User.Courts {
    export type CourtFilterData = {
        date: string;
    };
    export type CourtListData = {
        id: number;
        name: string;
        description: string | null;
        slots: Array<App.Data.User.Courts.CourtSlotData>;
        courtType: App.Data.Core.CourtTypes.CourtTypeSelectOptionData;
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
declare namespace App.Data.User.DiscountCodes {
    export type DiscountCodeData = {
        code: string;
        type: App.Enums.DiscountCodeType;
        value: number;
    };
}
declare namespace App.Data.User.Forms {
    export type BeginnerFormStoreData = {
        email: string;
        first_name: string;
        last_name: string;
        phone: string;
        age: number;
        groups: string;
        marketing_consent: boolean;
        service_consent: boolean;
    };
}
declare namespace App.Data.User.FutureMembers {
    export type FutureMemberStoreData = {
        email: string;
        first_name: string;
        last_name: string;
        phone: string | null;
        services: Array<any>;
        days?: Array<any>;
        times?: Array<any>;
        plan?: string;
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
declare namespace App.Data.User.Invoices {
    export type InvoiceListData = {
        id: number;
        date: string;
        price: number;
        vat: number;
        price_with_vat: number;
    };
}
declare namespace App.Data.User.Newsletter {
    export type NewsletterSubscribeData = {
        email: string;
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
declare namespace App.Data.User.PlanCourtTypeRules {
    export type PlanCourtTypeRuleData = {
        court_type_id: number;
        max_days_in_advance: number;
        cancel_hours_before: number;
    };
}
declare namespace App.Data.User.Plans {
    export type PlanData = {
        name: string;
        type: string;
        is_popular: boolean;
        courtTypeRules: Array<App.Data.User.PlanCourtTypeRules.PlanCourtTypeRuleData>;
        features: Array<App.Data.User.Plans.Features.PlanFeatureData>;
        prices: Array<App.Data.User.Plans.Prices.PlanPriceData>;
    };
}
declare namespace App.Data.User.Plans.Features {
    export type PlanFeatureData = {
        label: string;
        subFeatures: Array<App.Data.User.Plans.Features.PlanSubFeatureData>;
    };
    export type PlanSubFeatureData = {
        label: string;
    };
}
declare namespace App.Data.User.Plans.Prices {
    export type PlanPriceData = {
        id: number;
        periodicity_type: string;
        previous_price: number | null;
        price: number;
    };
}
declare namespace App.Data.User.Reservations {
    export type ReservationData = {
        id: number;
        courtName: string;
        courtType: App.Data.Core.CourtTypes.CourtTypeSelectOptionData;
        date: string;
        start_time: string;
        end_time: string;
        price_with_vat: number;
        refunded_amount: number;
        is_paid: boolean;
        is_past: number;
        cancelled_at: string | null;
        slots: Array<App.Data.User.Reservations.ReservationSlotData>;
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
        discount_code?: string | null;
        slots: Array<App.Data.User.Reservations.ReservationSlotStoreData>;
    };
}
declare namespace App.Data.User.Subscriptions {
    export type SubscribeData = {
        discount_code?: string | null;
    };
    export type SubscriptionData = {
        plan_price_id: number;
        started_at: string;
        expired_at: string;
        cancelled_at: string | null;
    };
}
declare namespace App.Enums {
    export type AdminRole = "superAdmin" | "employee";
    export type Day = "mon" | "tue" | "wed" | "thu" | "fri" | "sat" | "sun";
    export type DiscountCodeType = "percent" | "fixed";
    export type PaymentStatus = "pending" | "paid" | "cancelled" | "expired";
    export type Social = "google";
}
