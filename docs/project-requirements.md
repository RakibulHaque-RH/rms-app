---
noteId: "3a93f590388f11f185002b6b54db6a0d"
tags: []
---

# Restaurant Management System (RMS) - Implemented Requirements

## Document Info

- Version: 1.1
- Date: 2026-04-15
- Type: As-built requirements (what is already done)

## 1. Purpose

This document lists the requirements already implemented in the current RMS codebase.

## 2. Completed Requirements

### 2.1 Authentication and Access

1. Users can log in with email and password.
2. Inactive users are blocked from login.
3. Secure logout is implemented with session invalidation and token regeneration.
4. Protected pages require authentication middleware.
5. Manager/Admin role middleware is applied for inventory, staff, and reports.

### 2.2 Dashboard and Notifications

1. Dashboard shows daily orders and revenue.
2. Dashboard shows total revenue, available/occupied table counts, low-stock count, and total staff.
3. Dashboard shows recent orders and pending/preparing orders.
4. Notification page shows:
    - pending/preparing order alerts
    - ready order alerts
    - unpaid/partial payment alerts
    - low-stock alerts (for manager/admin)

### 2.3 Order Management

1. Create order with table, items, quantity, and notes.
2. Order number is auto-generated.
3. Validation enforces at least one order item.
4. Order totals are calculated from order items.
5. Order list supports filtering by status, payment status, and date.
6. Order status updates support: pending, preparing, ready, served, completed, cancelled.
7. Table status is set to occupied on order creation.
8. Table is auto-set to available when all active orders for that table are completed/cancelled.
9. Order deletion is implemented.
10. Receipt view is implemented.

### 2.4 Kitchen Workflow

1. Kitchen queue shows pending and preparing orders.
2. Kitchen queue is ordered oldest first.
3. Kitchen status update supports: pending, preparing, ready.

### 2.5 Menu and Recipe Management

1. Menu CRUD is implemented.
2. Menu supports category, name, description, price, availability, and image upload.
3. Ingredient mapping is required when creating/updating a menu item.
4. Duplicate ingredient entries are normalized per menu item.

### 2.6 Inventory Management

1. Inventory CRUD is implemented.
2. Low-stock filter is implemented using quantity <= min_quantity.
3. Stock is validated before order creation.
4. Stock is validated again before served/completed transition if not deducted yet.
5. Inventory is deducted once per order when moving to served/completed.

### 2.7 Table Management

1. Table CRUD is implemented.
2. Supported statuses: available, occupied, reserved, maintenance.
3. Manual table status update endpoint exists.

### 2.8 Staff Management

1. Staff list is implemented (excluding admin records in listing).
2. Staff create/update/delete is implemented for manager/admin.
3. Supported staff roles: manager, waiter, chef, cashier.
4. Passwords are hashed.
5. Staff active/inactive status is supported.

### 2.9 Payment Management

1. Manual payments are implemented for cash, card, bkash, and rocket.
2. Reference is required for card/bkash/rocket payments.
3. Overpayment is prevented by validation against remaining due.
4. Payment status update supports unpaid, partial, paid.
5. SSLCommerz online payment initiation is implemented.
6. SSLCommerz callback handlers are implemented for success/fail/cancel/ipn.
7. Gateway validation is performed before confirming success.
8. Payment transactions are stored with status and gateway response payload.
9. IPN handling is idempotent for already successful transactions.

### 2.10 Reports

1. Date-range reporting is implemented.
2. Metrics include total revenue, total orders, completed orders, cancelled orders.
3. Daily revenue trend is implemented.
4. Top items by sold quantity are implemented.
5. Category-wise revenue is implemented.
6. Most popular dish is computed.

### 2.11 User Settings

1. User can update profile (name, email, phone).
2. Email uniqueness is enforced on update.
3. Password change supports current password validation.

## 3. Technical Baseline Implemented

1. Framework: Laravel 10
2. Runtime: PHP 8.1+
3. Frontend build: Vite 5
4. HTTP integration: Laravel HTTP client/Guzzle
5. Auth stack: session auth (Sanctum present in project)

## 4. Partial or Open Items

1. Kitchen routes are authenticated but not currently restricted to chef-only role.
2. API layer is minimal (only auth:sanctum user endpoint).
3. Automated test coverage for all business-critical flows is not documented in this file.

## 5. Summary

The core RMS operations are implemented: authentication, order flow, kitchen flow, menu-recipe mapping, inventory deduction logic, table tracking, staff management, payments (manual and SSLCommerz), notifications, reporting, and user settings.
