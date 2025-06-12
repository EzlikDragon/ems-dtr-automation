# EMS + DTR System – Maasin City Hall (PHP/MySQL)

A fully functional Employee Management System + Daily Time Record (DTR) web application built for Maasin City Hall.  
Created by **Nicole Dominique Montederamos** during on-site deployment & internship.

---

## 🧠 Features

- Barcode scanning and automated time logging
- AM/PM time slot detection and tagging logic (LATE, EARLY OUT)
- Admin + Employee login portals
- DTR log history, employee management, and reports
- Dashboard interface and access control

---

## 🛠 Tech Stack

- PHP
- MySQL
- HTML/CSS/JS
- GitHub Actions (CI Simulation)
- XAMPP (Local Dev) / Totalh.net (Live Test)

---

## 🚦 GitHub CI/CD

Includes `.github/workflows/ems-ci.yml`  
Simulated CI config that validates repo pull via GitHub Actions

```yaml
name: EMS-DTR CI

on:
  push:
    branches: [ main ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Validate setup
        run: echo "✅ EMS+DTR project repo check passed"
