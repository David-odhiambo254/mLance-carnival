<?php

namespace App\Constants;

class Status
{

    const ENABLE = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO = 0;

    const VERIFIED = 1;
    const UNVERIFIED = 0;

    const PAYMENT_INITIATE = 0;
    const PAYMENT_SUCCESS = 1;
    const PAYMENT_PENDING = 2;
    const PAYMENT_REJECT = 3;

    const TICKET_OPEN = 0;
    const TICKET_ANSWER = 1;
    const TICKET_REPLY = 2;
    const TICKET_CLOSE = 3;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;

    const USER_ACTIVE = 1;
    const USER_BAN = 0;

    const KYC_UNVERIFIED = 0;
    const KYC_PENDING = 2;
    const KYC_VERIFIED = 1;

    const GOOGLE_PAY = 5001;

    const CUR_BOTH = 1;
    const CUR_TEXT = 2;
    const CUR_SYM = 3;

    const CATEGORY_FEATURED    = 1;
    const SUBCATEGORY_FEATURED = 1;
    const SUBCATEGORY_POPULAR  = 1;

    const GIG_DRAFT = 0;
    const GIG_PUBLISHED = 1;

    const GIG_PENDING = 0;
    const GIG_APPROVED = 1;
    const GIG_REJECTED = 2;

    const ORDER_PENDING   = 0;
    const ORDER_ACCEPTED  = 1;
    const ORDER_REJECTED  = 2;
    const ORDER_REPORTED  = 3;
    const ORDER_COMPLETED = 4;

    const PROJECT_PENDING   = 0;
    const PROJECT_ACCEPTED  = 1;
    const PROJECT_REJECTED  = 2;
    const PROJECT_REPORTED  = 3;
    const PROJECT_COMPLETED = 4;
}
