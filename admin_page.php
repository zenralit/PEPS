<?php
require 'connect_bd.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function getSalesByPeriod($conn, $startDate, $endDate) {
    $stmt = $conn->prepare("
        SELECT p.pname, u.firstname, u.lastname, u.email, pr.quantity, pr.price_at_purchase, pr.purchase_date 
        FROM purchases pr
        JOIN products p ON pr.product_id = p.product_id
        JOIN users u ON pr.user_id = u.user_id
        WHERE pr.purchase_date BETWEEN ? AND ?
        ORDER BY pr.purchase_date DESC
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    return $stmt->get_result();
}

function getCustomersByCity($conn) {
    $result = $conn->query("
        SELECT city, COUNT(*) as customer_count 
        FROM users 
        WHERE status = 'user'
        GROUP BY city 
        ORDER BY customer_count DESC
    ");
    return $result;
}

function getProductReviews($conn) {
    $result = $conn->query("
        SELECT p.pname, u.firstname, u.lastname, r.rating, r.reviews_text, r.reviews_date 
        FROM reviews r
        JOIN products p ON r.product_id = p.product_id
        JOIN users u ON r.user_id = u.user_id
        ORDER BY r.reviews_date DESC
        LIMIT 50
    ");
    return $result;
}

function getCartContents($conn) {
    $result = $conn->query("
        SELECT u.firstname, u.lastname, p.pname, c.quantity 
        FROM cart_items c
        JOIN users u ON c.user_id = u.user_id
        JOIN products p ON c.product_id = p.product_id
        WHERE c.quantity > 0
        ORDER BY u.lastname
    ");
    return $result;
}

function getAverageOrdersPerCustomer($conn) {
    $result = $conn->query("
        SELECT AVG(order_count) as avg_orders 
        FROM (
            SELECT user_id, COUNT(*) as order_count 
            FROM purchases 
            GROUP BY user_id
        ) as user_orders
    ");
    return $result->fetch_assoc()['avg_orders'];
}

function getCustomerOrderStats($conn, $userId) {
    $stmt = $conn->prepare("
        SELECT COUNT(*) as order_count, SUM(price_at_purchase * quantity) as total_spent 
        FROM purchases 
        WHERE user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getTotalSalesByPeriod($conn, $startDate, $endDate) {
    $stmt = $conn->prepare("
        SELECT SUM(price_at_purchase * quantity) as total_sales 
        FROM purchases 
        WHERE purchase_date BETWEEN ? AND ?
    ");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total_sales'];
}

function getTopProducts($conn) {
    $result = $conn->query("
        SELECT p.pname, COUNT(*) as purchase_count, SUM(pr.price_at_purchase * pr.quantity) as total_revenue
        FROM purchases pr
        JOIN products p ON pr.product_id = p.product_id
        GROUP BY p.pname
        ORDER BY total_revenue DESC
        LIMIT 10
    ");
    return $result;
}

function getCustomerPurchaseHistory($conn, $userId) {
    $stmt = $conn->prepare("
        SELECT p.pname, pr.quantity, pr.price_at_purchase, pr.purchase_date 
        FROM purchases pr
        JOIN products p ON pr.product_id = p.product_id
        WHERE pr.user_id = ?
        ORDER BY pr.purchase_date DESC
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result();
}
function getTopCustomers($conn) {
    $result = $conn->query("
        SELECT u.user_id, u.firstname, u.lastname, u.email,
               COUNT(p.id) as order_count, 
               SUM(p.price_at_purchase * p.quantity) as total_spent,  u.online_status, u.last_login
        FROM purchases p
        JOIN users u ON p.user_id = u.user_id
        GROUP BY u.user_id
        ORDER BY total_spent DESC
        LIMIT 10
    ");
    return $result;
}

function getLowStockProducts($conn, $threshold = 10) {
    $stmt = $conn->prepare("
        SELECT product_id, pname, quantity_in_stock, price
        FROM products
        WHERE quantity_in_stock < ?
        ORDER BY quantity_in_stock ASC
    ");
    $stmt->bind_param("i", $threshold);
    $stmt->execute();
    return $stmt->get_result();
}

function getSuppliersWithProducts($conn) {
    $result = $conn->query("
        SELECT s.company_name, s.contact_name, s.contact_pohone, s.email,
               GROUP_CONCAT(p.pname SEPARATOR ', ') as supplied_products
        FROM suppliers s
        LEFT JOIN suppliers_product sp ON s.supplier_id = sp.supplier_id
        LEFT JOIN products p ON sp.product_id = p.product_id
        GROUP BY s.supplier_id
        ORDER BY s.company_name
    ");
    return $result;
}

$result = null;
$functionName = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $functionName = $_POST['function'];
    
    switch ($functionName) {
        case 'sales_by_period':
            $result = getSalesByPeriod($conn, $_POST['start_date'], $_POST['end_date']);
            break;
        case 'customers_by_city':
            $result = getCustomersByCity($conn);
            break;
        case 'product_reviews':
            $result = getProductReviews($conn);
            break;
        case 'cart_contents':
            $result = getCartContents($conn);
            break;
        case 'avg_orders':
            $avg = getAverageOrdersPerCustomer($conn);
            $result = array('avg_orders' => $avg);
            break;
        case 'customer_stats':
            $result = getCustomerOrderStats($conn, $_POST['user_id']);
            break;
        case 'total_sales':
            $total = getTotalSalesByPeriod($conn, $_POST['start_date'], $_POST['end_date']);
            $result = array('total_sales' => $total);
            break;
        case 'top_products':
            $result = getTopProducts($conn);
            break;
        case 'customer_history':
            $result = getCustomerPurchaseHistory($conn, $_POST['user_id']);
            break;
            case 'top_customers':
                $result = getTopCustomers($conn);
                break;
            case 'low_stock':
                $threshold = isset($_POST['threshold']) ? (int)$_POST['threshold'] : 10;
                $result = getLowStockProducts($conn, $threshold);
                break;
            case 'suppliers_products':
                $result = getSuppliersWithProducts($conn);
                break;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель PEPS</title>
    <link rel="stylesheet" href="admin_page_css.css">
</head>
<body>
    <div class="container">
        <header>
            <a href="user_cabinet.php"><h1 style="color: green;">PEPS@ADMIN</h1></a>
            
        </header>
        
<?php 

$user_id = $_SESSION['user_id'];

$sql = "SELECT firstname, phone, lastname, email, city, status, online_status FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultt = $stmt->get_result();
if ($resultt->num_rows === 1) {
    $row = $resultt->fetch_assoc();
    $status = $row['status']; 
    switch ($status){
        case 'superadmin':
            echo ' <div class="flex-container">
       <div class="flex-items"><center><h2><a href="add_new_admin.php" style="color: white;" >супер админ панель</a></h2></center></div>
        
        <div class="flex-items"><center><h2><a href="add_prod.php" style="color: white;" >добавить новый товар</a></h2></center></div>
    </div>';
            break;
    }}
    
    ?></center>
        <div class="panel">
            <div class="card">
                <h3>Продажи за период</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="sales_by_period">
                    <div class="form-group">
                        <label for="start_date">Начальная дата:</label>
                        <input type="date" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Конечная дата:</label>
                        <input type="date" id="end_date" name="end_date" required>
                    </div>
                    <button type="submit">Показать продажи</button>
                </form>
            </div>

           

            <div class="card">
                <h3>Отзывы о товарах</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="product_reviews">
                        


                    <button type="submit">Показать отзывы</button>
                </form>
            </div>

                 <div class="card">
                <h3>Товары в корзинах</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="cart_contents">
                    <button type="submit">Показать корзины</button>
                </form>
            </div>

            <div class="card">
                <h3>Среднее количество заказов</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="avg_orders">
                    <button type="submit">Рассчитать</button>
<?php if ($functionName === 'avg_orders'): ?>
                    <div class="stat-card">
                        <div class="stat-value"><?php echo round($result['avg_orders'], 2); ?></div>
                    </div>
                    <?php endif ?>



                </form>
            </div>

             <div class="card">
                <h3>Топ товаров</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="top_products">
                    <button type="submit">Показать топ</button>
                </form>
            </div>

            <div class="card">
                <h3>Клиенты по городам</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="customers_by_city">
                    <button type="submit">Показать распределение</button>
                </form>
            </div>
            
           

            

           

            <div class="card">
                <h3>Общие продажи за период</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="total_sales">
                    <div class="form-group">
                        <label for="start_date_total">Начальная дата:</label>
                        <input type="date" id="start_date_total" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date_total">Конечная дата:</label>
                        <input type="date" id="end_date_total" name="end_date" required>
                    </div>
                    <button type="submit">Рассчитать</button>
                </form>
            </div>

             <div class="card">
                <h3>Самые активные клиенты</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="top_customers">
                    <button type="submit">Показать топ клиентов</button>
                </form>
            </div>

            <div class="card">
                <h3>История покупок клиента</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="customer_history">
                    <div class="form-group">
                        <label for="user_id_history">ID клиента:</label>
                        <input type="number" id="user_id_history" name="user_id" required>
                    </div>
                    <button type="submit">Показать историю</button>
                </form>
            </div>
          

            <div class="card">
                <h3>Товары с низким остатком</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="low_stock">
                    <div class="form-group">
                        <label for="threshold">Порог остатка:</label>
                        <input type="number" id="threshold" name="threshold" value="10" min="1">
                    </div>
                    <button type="submit">Показать товары</button>
                </form>
            </div>

            <div class="card">
                <h3>Статистика клиента</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="customer_stats">
                    <div class="form-group">
                        <label for="user_id">ID клиента:</label>
                        <input type="number" id="user_id" name="user_id" required>
                    </div>
                    <button type="submit">Получить статистику</button>
                </form>
            </div>

            <div class="card">
                <h3>Поставщики и их товары</h3>
                <form method="POST">
                    <input type="hidden" name="function" value="suppliers_products">
                    <button type="submit">Показать связи</button>
                </form>
            </div>
        </div>

        </div>


        <div class="results">
            <?php if ($result): ?>
                <?php if($functionName != 'avg_orders'): ?>
                <h2>Результаты: <?php echo $functionName; ?></h2>
                
                <?php if ($functionName === 'avg_orders'): ?>
                    <div class="stat-card">
                        <h3>Среднее количество заказов на клиента</h3>
                        <div class="stat-value"><?php echo round($result['avg_orders'], 2); ?></div>
                    </div>
                
                <?php elseif ($functionName === 'customer_stats'): ?>
                    <div class="stat-card">
                        <h3>Статистика клиента ID <?php echo $_POST['user_id']; ?></h3>
                        <div class="stat-value">Всего заказов: <?php echo $result['order_count']; ?></div>
                        <div class="stat-value">Общая сумма: $<?php echo number_format($result['total_spent'], 2); ?></div>
                    </div>
                
                <?php elseif ($functionName === 'total_sales'): ?>
                    <div class="stat-card">
                        <h3>Общие продажи с <?php echo $_POST['start_date']; ?> по <?php echo $_POST['end_date']; ?></h3>
                        <div class="stat-value">$<?php echo number_format($result, ); ?></div>
                    </div>
                
                <?php elseif (is_object($result) && $result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <?php 
                                $fields = $result->fetch_fields();
                                foreach ($fields as $field) {
                                    echo "<th>" . ucfirst(str_replace('_', ' ', $field->name)) . "</th>";
                                }
                                $result->data_seek(0);
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <?php foreach ($row as $value): ?>
                                        <td><?php echo htmlspecialchars($value); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Нет данных для отображения.</p>
                <?php endif; ?>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>