<?php
session_start();

require 'connect_bd_no.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $userId = (int)$_POST['user_id'];
        $conn->query("UPDATE users SET status = 'admin' WHERE user_id = $userId");
        $_SESSION['message'] = "Права администратора успешно предоставлены";
    } 
    elseif (isset($_POST['reject'])) {
        $userId = (int)$_POST['user_id'];
        $conn->query("UPDATE users SET status = 'user' WHERE user_id = $userId");
        $_SESSION['message'] = "Заявка отклонена";
    }
    elseif (isset($_POST['revoke_admin'])) {
        $userId = (int)$_POST['user_id'];
        if ($userId != $_SESSION['user_id']) { // Нельзя снять права с себя
            $conn->query("UPDATE users SET status = 'user' WHERE user_id = $userId");
            $_SESSION['message'] = "Права администратора отозваны";
        }
    }
    elseif (isset($_POST['make_superadmin'])) {
        $userId = (int)$_POST['user_id'];
        $conn->query("UPDATE users SET status = 'superadmin' WHERE user_id = $userId");
        $_SESSION['message'] = "Права суперадминистратора предоставлены";
    }
    elseif (isset($_POST['ban_user'])) {
        $userId = (int)$_POST['user_id'];
        $conn->query("UPDATE users SET status = 'banned' WHERE user_id = $userId");
        $_SESSION['message'] = "Пользователь заблокирован";
    }
    elseif (isset($_POST['unban_user'])) {
        $userId = (int)$_POST['user_id'];
        $conn->query("UPDATE users SET status = 'user' WHERE user_id = $userId");
        $_SESSION['message'] = "Пользователь разблокирован";
    }
}

if (isset($_SESSION['message'])) {
    echo "<div class='flash-message'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']);
}

$systemStats = $conn->query("
    SELECT 
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM users WHERE status = 'admin' OR status = 'superadmin') as total_admins,
        (SELECT COUNT(*) FROM users WHERE status = 'wait') as pending_requests,
        (SELECT COUNT(*) FROM products) as total_products,
        (SELECT COUNT(*) FROM purchases WHERE DATE(purchase_date) = CURDATE()) as today_orders,
        (SELECT SUM(price_at_purchase * quantity) FROM purchases WHERE DATE(purchase_date) = CURDATE()) as today_revenue
")->fetch_assoc();

$waitingUsers = $conn->query("
    SELECT u.*, 
           (SELECT COUNT(*) FROM purchases WHERE user_id = u.user_id) as order_count,
           (SELECT SUM(price_at_purchase * quantity) FROM purchases WHERE user_id = u.user_id) as total_spent,
           (SELECT COUNT(*) FROM reviews WHERE user_id = u.user_id) as reviews_count
    FROM users u
    WHERE status = 'wait'
    ORDER BY created_at DESC
");

$currentAdmins = $conn->query("
    SELECT u.*, 
           (SELECT COUNT(*) FROM purchases WHERE user_id = u.user_id) as order_count,
           (SELECT SUM(price_at_purchase * quantity) FROM purchases WHERE user_id = u.user_id) as total_spent
        
    FROM users u
    WHERE status IN ('admin', 'superadmin')
    ORDER BY 
        CASE WHEN status = 'superadmin' THEN 0 ELSE 1 END,
        created_at
");

$recentOrders = $conn->query("
    SELECT p.*, u.firstname, u.lastname, pr.pname as product_name
    FROM purchases p
    JOIN users u ON p.user_id = u.user_id
    JOIN products pr ON p.product_id = pr.product_id
    ORDER BY p.purchase_date DESC
    LIMIT 5
");

$bannedUsers = $conn->query("
    SELECT * FROM users WHERE status = 'banned' ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEPS</title>
    <link rel="stylesheet" href="admins_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="container">
        <header>
          <a href="test_admin.php"> <h1><i class="fas" ></i> PEPS@SUPERADMIN</h1></a> 
        </header>

        <div class="section">
            <h2><i class="fas fa-chart-line"></i> Общая статистика</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Всего пользователей</div>
                    <div class="stat-value"><?= $systemStats['total_users'] ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Администраторов</div>
                    <div class="stat-value"><?= $systemStats['total_admins'] ?></div>
                    <div class="stat-details"><?= $systemStats['pending_requests'] ?> заявок</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Товаров в каталоге</div>
                    <div class="stat-value"><?= $systemStats['total_products'] ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Заказов сегодня</div>
                    <div class="stat-value"><?= $systemStats['today_orders'] ?></div>
                    <div class="stat-details"><?= number_format($systemStats['today_revenue'], 2) ?> €</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="tabs">
                <div class="tab active" onclick="openTab(event, 'requests')"><i class="fas fa-user-clock"></i> Заявки</div>
                <div class="tab" onclick="openTab(event, 'admins')"><i class="fas fa-user-shield"></i> Администраторы</div>
                <div class="tab" onclick="openTab(event, 'banned')"><i class="fas fa-user-slash"></i> Заблокированные</div>
                <div class="tab" onclick="openTab(event, 'orders')"><i class="fas fa-shopping-cart"></i> Последние заказы</div>
            </div>

            <div id="requests" class="tab-content active">
                <h3>Заявки на права администратора</h3>
                
                <?php if ($waitingUsers->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Email</th>
                                <th>Активность</th>
                                <th>Дата регистрации</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $waitingUsers->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $user['user_id'] ?></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="<?= htmlspecialchars($user['profile_pic'] ?? 'uploads/base.png') ?>" class="user-avatar" alt="Аватар">
                                            <div>
                                                <div><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></div>
                                                <div style="font-size: 0.8em; color: var(--text-secondary);"><?= htmlspecialchars($user['city'] ?? 'Не указан') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <div style="display: flex; gap: 15px;">
                                            <div>
                                                <div style="font-size: 0.9em;">Заказов</div>
                                                <div><?= $user['order_count'] ?></div>
                                            </div>
                                            <div>
                                                <div style="font-size: 0.9em;">Отзывов</div>
                                                <div><?= $user['reviews_count'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <form method="POST">
                                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                                <button type="submit" name="approve" class="btn btn-approve"><i class="fas fa-check"></i> Одобрить</button>
                                                <button type="submit" name="reject" class="btn btn-reject"><i class="fas fa-times"></i> Отклонить</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-user-clock" style="font-size: 2em; margin-bottom: 10px;"></i>
                        <p>Нет заявок на рассмотрении</p>
                    </div>
                <?php endif; ?>
            </div>

            <div id="admins" class="tab-content">
               
                
                <h3>Текущие администраторы</h3>
                
                <?php if ($currentAdmins->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Email</th>
                                <th>роль</th>
                                <th>заказов</th>
                                <th>действия</th>
                                <th>статус</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($admin = $currentAdmins->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $admin['user_id'] ?></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="<?= htmlspecialchars($admin['profile_pic'] ?? 'uploads/base.png') ?>" class="user-avatar" alt="Аватар">
                                            <div>
                                                <div><?= htmlspecialchars($admin['firstname'] . ' ' . $admin['lastname']) ?></div>
                                                <div style="font-size: 0.8em; color: var(--text-secondary);"><?= htmlspecialchars($admin['city'] ?? 'Не указан') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($admin['email']) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $admin['status'] === 'superadmin' ? 'superadmin' : 'admin' ?>">
                                            <?= $admin['status'] === 'superadmin' ? 'Суперадмин' : 'Админ' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 15px;">
                                            <div>
                                                <div><?= $admin['order_count'] ?></div>
                                            </div>
                                            
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($admin['status'] !== 'superadmin'): ?>
                                                <form method="POST">
                                                    <input type="hidden" name="user_id" value="<?= $admin['user_id'] ?>">
                                                    <button type="submit" name="make_superadmin" class="btn btn-superadmin"><i class="fas fa-crown"></i> Суперадмин</button>
                                                    <button type="submit" name="revoke_admin" class="btn btn-danger"><i class="fas fa-user-minus"></i> Отозвать</button>
                                                </form>
                                            <?php else: ?>
                                                <span style="color: var(--text-secondary); font-size: 0.9em;">Высший уровень доступа</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 15px;">
                                            <div>
                                                
                                                <div><?= $admin['online_status'] ?></div>
                                            </div>
                                            
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-user-shield" style="font-size: 2em; margin-bottom: 10px;"></i>
                        <p>Нет зарегистрированных администраторов</p>
                    </div>
                <?php endif; ?>
            </div>

            <div id="banned" class="tab-content">
                <h3>Заблокированные пользователи</h3>
                
                <?php if ($bannedUsers->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Email</th>
                                <th>Дата блокировки</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $bannedUsers->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $user['user_id'] ?></td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <img src="<?= htmlspecialchars($user['profile_pic'] ?? 'images/default-avatar.jpg') ?>" class="user-avatar" alt="Аватар">
                                            <div>
                                                <div><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></div>
                                                <div style="font-size: 0.8em; color: var(--text-secondary);"><?= htmlspecialchars($user['city'] ?? 'Не указан') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= date('d.m.Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <form method="POST">
                                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                                <button type="submit" name="unban_user" class="btn btn-success"><i class="fas fa-user-check"></i> Разблокировать</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-user-slash" style="font-size: 2em; margin-bottom: 10px;"></i>
                        <p>Нет заблокированных пользователей</p>
                    </div>
                <?php endif; ?>
            </div>

            <div id="orders" class="tab-content">
                <h3>Последние заказы</h3>
                
                <?php if ($recentOrders->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Товар</th>
                                <th>Покупатель</th>
                                <th>Количество</th>
                                <th>Сумма</th>
                                <th>Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $recentOrders->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $order['id'] ?></td>
                                    <td><?= htmlspecialchars($order['product_name']) ?></td>
                                    <td><?= htmlspecialchars($order['firstname'] . ' ' . $order['lastname']) ?></td>
                                    <td><?= $order['quantity'] ?></td>
                                    <td><?= number_format($order['price_at_purchase'] * $order['quantity'], 2) ?> €</td>
                                    <td><?= date('d.m.Y H:i', strtotime($order['purchase_date'])) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        <i class="fas fa-shopping-cart" style="font-size: 2em; margin-bottom: 10px;"></i>
                        <p>Нет данных о заказах</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function openTab(evt, tabName) {
            const tabContents = document.getElementsByClassName("tab-content");
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }
            
            const tabs = document.getElementsByClassName("tab");
            for (let i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove("active");
            }
            
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
        
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.flash-message');
            flashMessages.forEach(msg => {
                msg.style.display = 'none';
            });
        }, 3000);

        setInterval(function() {
    fetch('update_status.php');
}, 60000);
    </script>
</body>
</html>