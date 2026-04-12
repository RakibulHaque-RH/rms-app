---
noteId: "c3529540356e11f1acb16fdc187d04af"
tags: []
---

# Restaurant Management System (RMS) - Product Requirements Document

Version: 1.0  
Date: 2026-04-11  
Project Type: Web-based Restaurant Management System

## 1. Product Overview

The Restaurant Management System (RMS) is a role-based web application for restaurant operations. It supports end-to-end workflows including order intake, kitchen processing, table status management, inventory tracking, staff administration, payment processing (offline and online via SSLCommerz), receipt generation, notifications, and business reporting.

The product is designed for daily operational control with clear separation of roles:

- Admin
- Manager
- Waiter
- Chef
- Cashier

## 2. Product Goals

1. Reduce order processing delays through a centralized digital workflow.
2. Improve order accuracy and status visibility between floor staff and kitchen.
3. Enable partial/full payment tracking with secure online payment support.
4. Improve stock control by connecting menu recipes to inventory deduction.
5. Provide managers with actionable performance and revenue reports.

## 3. Scope

### 3.1 In Scope

1. Role-based authentication and authorization.
2. Order lifecycle management.
3. Kitchen display and status transitions.
4. Menu and recipe (ingredient mapping) management.
5. Table management and occupancy tracking.
6. Inventory CRUD and low-stock awareness.
7. Staff management for operational roles.
8. Payment capture (manual and SSLCommerz online flow).
9. Notification center for operational alerts.
10. Sales and performance reports by date range.

### 3.2 Out of Scope

1. Customer-facing mobile app.
2. Multi-branch enterprise hierarchy.
3. Tax/VAT engine per jurisdiction.
4. Supplier purchase order workflow automation.
5. Loyalty program and CRM.

## 4. Stakeholders and User Roles

1. Restaurant Owner: Strategic oversight and KPI visibility.
2. Admin: Full access and system-level controls.
3. Manager: Operations supervision, staff/inventory/report management.
4. Waiter: Order creation, order updates, table flow.
5. Chef: Kitchen queue and preparation status updates.
6. Cashier: Payment handling and receipt process.

## 5. Assumptions and Constraints

1. Users are pre-onboarded by Admin/Manager where applicable.
2. Stable internet is available for online payment callbacks.
3. SSLCommerz credentials are configured in environment settings before online payments.
4. Kitchen workflow follows defined statuses: pending, preparing, ready.
5. Order workflow supports statuses: pending, preparing, ready, served, completed, cancelled.

## 6. System Requirements

### 6.1 Technology Stack

1. Backend Framework: Laravel 10.x
2. Language: PHP 8.1+
3. Frontend Build Tool: Vite 5.x
4. HTTP Client: Guzzle / Laravel HTTP client
5. Authentication: Laravel session auth + Sanctum available
6. Database: MySQL or compatible relational database

### 6.2 Software Prerequisites (Development/Deployment)

1. PHP 8.1 or higher
2. Composer 2.x
3. Node.js 18+ and npm
4. MySQL 8+ (or MariaDB equivalent)
5. Web server (Apache/Nginx) for production
6. Git for version control

### 6.3 Minimum Hardware Requirements

1. Application Server (minimum):
   CPU: 2 cores
   RAM: 4 GB
   Storage: 20 GB SSD
2. Database Server (minimum, small traffic):
   CPU: 2 cores
   RAM: 4 GB
   Storage: 30 GB SSD
3. Client Devices:
   Modern browser (Chrome/Edge/Firefox/Safari), 4 GB RAM recommended

### 6.4 Recommended Production Baseline

1. App Server: 4 vCPU, 8 GB RAM
2. Managed DB with daily backup and point-in-time recovery
3. HTTPS enabled with valid TLS certificate
4. Centralized log retention and monitoring

## 7. Functional Requirements

### 7.1 Authentication and Access Control

1. FR-AUTH-01: The system shall allow users to log in using email and password.
2. FR-AUTH-02: The system shall block inactive users from logging in.
3. FR-AUTH-03: The system shall provide secure logout and session invalidation.
4. FR-AUTH-04: The system shall enforce role-based access for protected modules.
5. FR-AUTH-05: Admin shall have override access where role middleware allows Admin fallback.

### 7.2 Dashboard and Notifications

1. FR-DASH-01: The system shall show daily order count and revenue.
2. FR-DASH-02: The system shall show total revenue, table occupancy, low-stock count, and staffing indicators.
3. FR-NOTIF-01: The system shall display pending/preparing order alerts.
4. FR-NOTIF-02: The system shall display ready-to-serve alerts.
5. FR-NOTIF-03: The system shall display unpaid/partial payment alerts.
6. FR-NOTIF-04: For Manager/Admin, the system shall display low-stock alerts.

### 7.3 Order Management

1. FR-ORD-01: The system shall allow creating an order with table, items, quantities, and notes.
2. FR-ORD-02: The system shall generate a unique order number automatically.
3. FR-ORD-03: The system shall validate that at least one item exists in an order.
4. FR-ORD-04: The system shall calculate item subtotal and order total amount.
5. FR-ORD-05: The system shall allow filtering orders by status, payment status, and date.
6. FR-ORD-06: The system shall support order status updates across lifecycle stages.
7. FR-ORD-07: The system shall free the table automatically when all linked active orders are completed/cancelled.
8. FR-ORD-08: The system shall support order deletion.
9. FR-ORD-09: The system shall provide receipt view for an order.

### 7.4 Kitchen Workflow

1. FR-KITCH-01: The system shall show kitchen queue for pending and preparing orders.
2. FR-KITCH-02: Chef shall update order status among pending, preparing, ready.
3. FR-KITCH-03: Kitchen queue shall prioritize oldest orders first.

### 7.5 Inventory and Recipe-Controlled Stock

1. FR-INV-01: The system shall allow inventory CRUD operations (manager/admin).
2. FR-INV-02: The system shall support low-stock filtering where quantity <= min_quantity.
3. FR-MENU-01: The system shall allow menu CRUD including category, price, image, availability.
4. FR-MENU-02: The system shall require ingredient mapping for menu save/update.
5. FR-MENU-03: The system shall allow mapping multiple inventory items to a menu item with quantity_per_dish.
6. FR-STOCK-01: The system shall validate sufficient stock before order creation.
7. FR-STOCK-02: The system shall validate stock before moving to served/completed if inventory was not yet deducted.
8. FR-STOCK-03: The system shall deduct inventory once on served/completed transition and store deduction timestamp.

### 7.6 Table Management

1. FR-TBL-01: The system shall allow table CRUD operations.
2. FR-TBL-02: The system shall support table statuses: available, occupied, reserved, maintenance.
3. FR-TBL-03: The system shall update table status manually where permitted.
4. FR-TBL-04: The system shall mark table occupied when a new order is created.

### 7.7 Staff Management

1. FR-STAFF-01: Manager/Admin shall create staff with role waiter, chef, cashier, or manager.
2. FR-STAFF-02: The system shall store hashed passwords.
3. FR-STAFF-03: Manager/Admin shall update staff profile, role, status, and password.
4. FR-STAFF-04: Manager/Admin shall remove staff records.

### 7.8 Payment Management

1. FR-PAY-01: The system shall support manual payment recording for cash, card, bKash, and Rocket.
2. FR-PAY-02: The system shall require payment reference for card/bKash/Rocket methods.
3. FR-PAY-03: The system shall prevent payment over-collection beyond remaining due.
4. FR-PAY-04: The system shall set payment status as unpaid, partial, or paid based on paid_amount and total_amount.
5. FR-PAY-05: The system shall initiate SSLCommerz session for online payments.
6. FR-PAY-06: The system shall handle success/fail/cancel callback routes.
7. FR-PAY-07: The system shall verify payment using validation data before final confirmation.
8. FR-PAY-08: The system shall store gateway transactions, responses, and final state.
9. FR-PAY-09: The system shall support idempotent behavior for repeat IPN when transaction already marked success.

### 7.9 Reporting

1. FR-REP-01: Manager/Admin shall view report metrics by date range.
2. FR-REP-02: Reports shall include total revenue, total orders, completed orders, cancelled orders.
3. FR-REP-03: Reports shall include daily revenue trend.
4. FR-REP-04: Reports shall include top menu items and category-wise revenue.
5. FR-REP-05: Reports shall show most popular dish in selected date range.

### 7.10 User Settings

1. FR-SET-01: Users shall update personal name, email, and phone.
2. FR-SET-02: Users shall update password with current password validation.
3. FR-SET-03: Email uniqueness shall be enforced during profile updates.

## 8. Non-Functional Requirements

### 8.1 Performance

1. NFR-PERF-01: Typical page responses should complete within 2 seconds under normal load.
2. NFR-PERF-02: Dashboard and order list queries should support pagination for scalable retrieval.
3. NFR-PERF-03: Payment gateway operations should enforce request timeout and error handling.

### 8.2 Security

1. NFR-SEC-01: Passwords must be hashed and never stored in plain text.
2. NFR-SEC-02: Role-based authorization must restrict sensitive modules.
3. NFR-SEC-03: Sessions must be regenerated on login to reduce fixation risk.
4. NFR-SEC-04: Sensitive payment callbacks must be validated before state mutation.
5. NFR-SEC-05: Application must run behind HTTPS in production.

### 8.3 Availability and Reliability

1. NFR-REL-01: The system should target 99.5% monthly availability for business hours.
2. NFR-REL-02: Database backup shall run daily with verified restore process.
3. NFR-REL-03: Payment transaction states must be auditable via payment_transactions records.

### 8.4 Usability

1. NFR-USE-01: Core workflows (create order, update status, payment) should require minimal clicks.
2. NFR-USE-02: UI labels must use clear restaurant domain terms.
3. NFR-USE-03: Validation errors must be displayed near relevant input context.

### 8.5 Maintainability

1. NFR-MNT-01: Code shall follow MVC separation with controller-model-view modularity.
2. NFR-MNT-02: Database changes shall be versioned through migrations.
3. NFR-MNT-03: Business-critical flows should be covered by automated tests.

### 8.6 Compatibility

1. NFR-COMP-01: The system shall support current versions of major browsers.
2. NFR-COMP-02: Responsive rendering should support desktop and tablet operations.

## 9. Data Requirements

### 9.1 Core Entities

1. Users: identity, role, phone, active status.
2. Menus: category, price, availability, image.
3. Tables: number, capacity, status, location.
4. Orders: order number, table, user, status, totals, payment state, deduction timestamp.
5. Order Items: menu item, quantity, unit price, subtotal, notes.
6. Inventory: quantity, min threshold, cost, supplier.
7. Menu Ingredients: many-to-many map between menu and inventory with per-dish quantity.
8. Payment Transactions: gateway transaction metadata and callback payload.

### 9.2 Data Integrity Rules

1. order_number and transaction_id must be unique.
2. User email must be unique.
3. Table number must be unique.
4. Menu ingredient pair (menu_id, inventory_id) must be unique.
5. Foreign-key constraints with cascading delete apply to dependent records.

## 10. Business Rules

1. Orders cannot be created if required stock is insufficient.
2. Inventory deduction occurs once when order reaches served/completed and has not been deducted before.
3. A table is automatically set to occupied on order creation.
4. A table returns to available only when no active orders remain.
5. Payment status is derived from cumulative paid amount versus total order amount.
6. For non-cash digital/manual methods, payment reference is mandatory.

## 11. External Integrations

1. Payment Gateway: SSLCommerz
2. Required configuration:
   store_id
   store_password
   base_url
3. Callback endpoints:
   success
   fail
   cancel
   ipn

## 12. Reporting and Analytics Requirements

1. Date-range parameterization is required.
2. Revenue reports exclude cancelled orders for revenue totals.
3. Top items are ranked by sold quantity.
4. Category reports aggregate subtotal by menu category.

## 13. Acceptance Criteria (High-Level)

1. Admin/Manager can manage inventory, staff, and reports successfully.
2. Waiter can create and progress orders without unauthorized module access.
3. Chef sees pending/preparing queue and can update kitchen status.
4. Cashier can process partial and full payments correctly.
5. Online payment success callback updates order payment status and transaction log.
6. Insufficient stock blocks order flow with user-visible validation.
7. Low-stock and pending operational notifications are visible in notification center.

## 14. Risks and Mitigations

1. Risk: Payment callback mismatch or delayed IPN.
   Mitigation: Store raw callback payload, validate transaction status, and support idempotent handling.
2. Risk: Overselling under concurrent order creation.
   Mitigation: Enforce transactional stock checks and row-level locking in future hardening.
3. Risk: Unauthorized access from role misconfiguration.
   Mitigation: Middleware-based authorization and periodic access audit.

## 15. Future Enhancements

1. Multi-branch support with branch-level access control.
2. Customer-facing QR menu and self-ordering.
3. Supplier purchase order and stock replenishment workflow.
4. Tax, discount, and promo engine.
5. Advanced analytics with exportable dashboards.
6. Offline-first mode for temporary connectivity loss.

## 16. Traceability Summary

The document requirements map directly to implemented modules in routes, controllers, models, and migrations. This PRD can serve as both:

1. Product planning baseline.
2. Software requirements baseline for testing and UAT sign-off.
