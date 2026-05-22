# BOM Management System

## Overview
A comprehensive Bill of Materials (BOM) Management Module for manufacturing/procurement workflow automation.

## Features
- BOM file upload (Excel/CSV) with automatic parsing
- Auto inventory check against each line item
- Auto material allocation for in-stock items
- Auto purchase intent generation for out-of-stock/partial items
- Role-based access control (Admin, Purchase Dept, Engineer, Store Manager)
- Email notifications for purchase intents and allocations
- Real-time status updates using polling
- API endpoints for integration

## System Requirements
- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js & NPM (for frontend assets)

## Installation Steps

### 1. Clone the Repository
```bash
git clone <repository-url>
cd bom-management