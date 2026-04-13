# Page Clerk (Petugas) Quick Reference

## Access the Clerk Dashboard

1. Log in with a user account that has the `petugas` role
2. You'll be redirected to `/petugas/dashboard`

## Main Features Available

### 1. **Monitor Book Returns** 📥

- **Route:** `/petugas/monitor-returns`
- **Sidebar:** "Memantau Pengembalian"
- **Features:**
    - Filter by: Overdue, Today, Next 3 Days
    - Search by borrower name or book title
    - Record returns with book condition (Good/Damaged)
    - View return date and late status

### 2. **Approve Loan Requests** ✅

- **Route:** `/petugas/persetujuan-peminjaman`
- **Sidebar:** "Menyetujui Peminjaman"
- **Features:**
    - View pending loan requests
    - Approve or reject loans
    - Automatic stock adjustment

### 3. **View Book Data** 📚

- **Route:** `/petugas/books`
- **Sidebar:** "Data Buku"
- **Features:**
    - Complete book catalog
    - Filter by category
    - Search by title, author, or ISBN
    - View book details and borrowing history
    - See stock information (Total, Available, Borrowed)

### 4. **View Borrower Data** 👥

- **Route:** `/petugas/borrowers`
- **Sidebar:** "Data Peminjam"
- **Features:**
    - List all library members
    - Search by name or email
    - View borrower profile
    - See borrowing history and statistics
    - Track active and total loans

### 5. **Generate Reports** 📊

- **Route:** `/petugas/reporting`
- **Sidebar:** "Laporan Peminjaman"
- **Features:**
    - Select date range
    - View borrowing statistics
    - Export to CSV format
    - Export to HTML for printing
    - See top 10 books and borrowers

### 6. **Manage Profile** 👤

- **Route:** `/petugas/profile`
- **Sidebar:** "Profil Saya"
- **Features:**
    - View and edit name
    - Update email address
    - View role and join date
    - **Change password** with security verification

### 7. **Change Password** 🔐

- **Route:** `/petugas/change-password`
- **Accessible from:** Profile page
- **Features:**
    - Verify current password
    - Set new password (min 8 characters)
    - Confirm new password

## Default Navigation

### Sidebar Menu Structure

```
├─ Menu Utama
│  └─ Dashboard
│
├─ Tugas Petugas
│  ├─ Menyetujui Peminjaman (approve loans)
│  ├─ Memantau Pengembalian (monitor returns)
│  └─ Laporan Peminjaman (generate reports)
│
├─ Data
│  ├─ Data Buku (books)
│  └─ Data Peminjam (borrowers)
│
└─ Akun
   └─ Profil Saya (profile & password)
```

## Quick Actions Dashboard

The dashboard includes quick action buttons for:

1. Approve Loans
2. Monitor Returns
3. Print Reports
4. Book Data
5. Borrower Data
6. Profile

## Key Shortcuts

| Action          | URL                               |
| --------------- | --------------------------------- |
| Dashboard       | `/petugas/dashboard`              |
| Approve Loans   | `/petugas/persetujuan-peminjaman` |
| Monitor Returns | `/petugas/monitor-returns`        |
| Reports         | `/petugas/reporting`              |
| Books           | `/petugas/books`                  |
| Borrowers       | `/petugas/borrowers`              |
| Profile         | `/petugas/profile`                |
| Change Password | `/petugas/change-password`        |

## Report Export Formats

### CSV Export

- UTF-8 encoded for Excel compatibility
- Includes: Borrower Name, Email, Book Title, Borrow Date, Return Date, Status, Return Date

### HTML Export

- Printable format
- Table with all borrowing details
- Can be saved as PDF via browser

## Statistics Tracked

### Borrowing Statistics

- **Total Borrowings:** Count of all loans in date range
- **Completed Returns:** Books successfully returned
- **Late Returns:** Books not returned by due date

### Popular Items

- **Top 10 Books:** Most frequently borrowed
- **Top 10 Borrowers:** Most active members

## Password Policy

- Minimum 8 characters
- Password confirmation required
- Current password verification for security

## Notes

- Changes to book stock are automatic when returns are recorded
- Late status is determined by comparing due date with current date
- All reports use server date for consistency
- Profile updates are immediate
- Password changes take effect immediately on next login
