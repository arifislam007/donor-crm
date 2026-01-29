#!/bin/bash
# Test and Setup Script for NGO Donor Management System
# Run this script to test and set up the application

echo "=========================================="
echo "NGO Donor Management System - Setup Test"
echo "=========================================="

# Navigate to project directory
cd "$(dirname \"$0\")\"

echo ""
echo "Step 1: Stopping existing containers..."
docker-compose down 2>/dev/null || true

echo ""
echo "Step 2: Starting Docker containers..."
docker-compose up -d

# Wait for PostgreSQL to be ready
echo ""
echo "Step 3: Waiting for PostgreSQL to be ready..."
for i in {1..30}; do
    if docker-compose exec -T db pg_isready -U postgres -d ngo_donor_system > /dev/null 2>&1; then
        echo "✓ PostgreSQL is ready!"
        break
    fi
    echo "  Waiting for PostgreSQL... ($i/30)"
    sleep 2
done

echo ""
echo "Step 4: Running database setup..."
docker-compose exec -T app php setup.php

echo ""
echo "=========================================="
echo "Setup complete!"
echo ""
echo "Access the application:"
echo "  - Web: http://localhost:8080"
echo "  - Admin: http://localhost:8080/login"
echo "  - phpMyAdmin: http://localhost:8081"
echo ""
echo "Admin Credentials:"
echo "  Email: admin@ngodonation.org"
echo "  Password: admin123"
echo "=========================================="
