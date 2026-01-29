#!/bin/bash
# Docker Deployment Test Script
# NGO Donor Management System

echo "=========================================="
echo "  NGO Donor System - Docker Test Suite   "
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Test counter
TESTS_PASSED=0
TESTS_FAILED=0

# Function to run test
run_test() {
    local test_name="$1"
    local test_command="$2"
    
    echo -n "Testing: $test_name... "
    if eval "$test_command" > /dev/null 2>&1; then
        echo -e "${GREEN}PASSED${NC}"
        ((TESTS_PASSED++))
    else
        echo -e "${RED}FAILED${NC}"
        ((TESTS_FAILED++))
    fi
}

echo "Step 1: Container Status Tests"
echo "-------------------------------"

run_test "App container running" "docker compose ps | grep -q ngo-app"
run_test "Web container running" "docker compose ps | grep -q ngo-web"
run_test "Database container running" "docker compose ps | grep -q ngo-db"
run_test "PHPMyAdmin container running" "docker compose ps | grep -q ngo-phpmyadmin"

echo ""
echo "Step 2: Health Check Tests"
echo "---------------------------"

run_test "App health check" "docker inspect --format='{{.State.Health.Status}}' ngo-app 2>/dev/null | grep -q healthy"
run_test "Database health check" "docker inspect --format='{{.State.Health.Status}}' ngo-db 2>/dev/null | grep -q healthy"

echo ""
echo "Step 3: Network Tests"
echo "---------------------"

run_test "App connected to network" "docker network inspect ngo-donor-system_ngo-network 2>/dev/null | grep -q ngo-app"
run_test "Web connected to network" "docker network inspect ngo-donor-system_ngo-network 2>/dev/null | grep -q ngo-web"
run_test "Database connected to network" "docker network inspect ngo-donor-system_ngo-network 2>/dev/null | grep -q ngo-db"

echo ""
echo "Step 4: Application Endpoint Tests"
echo "-----------------------------------"

run_test "Web server responding on port 8080" "curl -s -o /dev/null -w '%{http_code}' http://localhost:8080/ | grep -q '200\|302'"
run_test "PHPMyAdmin responding on port 8081" "curl -s -o /dev/null -w '%{http_code}' http://localhost:8081/ | grep -q '200'"

echo ""
echo "Step 5: Database Tests"
echo "----------------------"

run_test "Database accepting connections" "docker compose exec -T db mysqladmin ping -h localhost -u root -proot_password 2>/dev/null | grep -q 'alive'"
run_test "Users table exists" "docker compose exec -T db mysql -u ngo_user -pngo_password ngo_donor_system -e 'SELECT COUNT(*) FROM users;' 2>/dev/null | grep -q '[0-9]'"
run_test "Projects table exists" "docker compose exec -T db mysql -u ngo_user -pngo_password ngo_donor_system -e 'SELECT COUNT(*) FROM projects;' 2>/dev/null | grep -q '[0-9]'"
run_test "Donations table exists" "docker compose exec -T db mysql -u ngo_user -pngo_password ngo_donor_system -e 'SELECT COUNT(*) FROM donations;' 2>/dev/null | grep -q '[0-9]'"

echo ""
echo "=========================================="
echo "  Test Results Summary"
echo "=========================================="
echo -e "Tests Passed: ${GREEN}$TESTS_PASSED${NC}"
echo -e "Tests Failed: ${RED}$TESTS_FAILED${NC}"
echo ""

if [ $TESTS_FAILED -eq 0 ]; then
    echo -e "${GREEN}All tests passed! Application is running correctly.${NC}"
    echo ""
    echo "Access your application at:"
    echo "  - Application: http://localhost:8080"
    echo "  - Admin Panel: http://localhost:8080/admin"
    echo "  - PHPMyAdmin: http://localhost:8081"
    exit 0
else
    echo -e "${YELLOW}Some tests failed. Check the logs for details.${NC}"
    echo ""
    echo "Useful commands:"
    echo "  docker compose logs -f"
    echo "  docker compose logs app"
    echo "  docker compose logs web"
    echo "  docker compose logs db"
    exit 1
fi
