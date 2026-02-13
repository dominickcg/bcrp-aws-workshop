<?php
// Incluir configuración de base de datos
require_once 'config.php';

// Inicializar variables
$mensaje_exito = '';
$mensaje_error = '';

// Conectar a la base de datos
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }
    
    // Crear tabla si no existe
    $sql_create = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        mensaje TEXT NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($sql_create);
    
    // Procesar formulario si se envió
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
        $nombre = $conn->real_escape_string($_POST['nombre']);
        $email = $conn->real_escape_string($_POST['email']);
        $mensaje = $conn->real_escape_string($_POST['mensaje']);
        
        $sql_insert = "INSERT INTO messages (nombre, email, mensaje) VALUES ('$nombre', '$email', '$mensaje')";
        
        if ($conn->query($sql_insert)) {
            $mensaje_exito = "¡Mensaje guardado exitosamente!";
        } else {
            $mensaje_error = "Error al guardar: " . $conn->error;
        }
    }
    
    // Obtener todos los mensajes
    $sql_select = "SELECT * FROM messages ORDER BY fecha DESC";
    $result = $conn->query($sql_select);
    
} catch (Exception $e) {
    $mensaje_error = "Error de conexión a la base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop AWS - Aplicación de Prueba</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        header {
            background-color: #232F3E;
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        header p {
            font-size: 1.2em;
            color: #FF9900;
        }
        
        .content {
            background-color: white;
            padding: 40px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-section {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 40px;
            border: 2px solid #e9ecef;
        }
        
        .form-section h2 {
            color: #232F3E;
            margin-bottom: 20px;
            font-size: 1.8em;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ced4da;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus {
            outline: none;
            border-color: #FF9900;
        }
        
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        button {
            background-color: #FF9900;
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.1s;
        }
        
        button:hover {
            background-color: #ec8b00;
            transform: translateY(-2px);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .table-section {
            margin-top: 40px;
        }
        
        .table-section h2 {
            color: #232F3E;
            margin-bottom: 20px;
            font-size: 1.8em;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        thead {
            background-color: #232F3E;
            color: white;
        }
        
        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        tbody tr:last-child td {
            border-bottom: none;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: white;
            font-size: 0.9em;
        }
        
        .instance-info {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #0066cc;
        }
        
        .instance-info strong {
            color: #0066cc;
        }
        
        @media (max-width: 768px) {
            header h1 {
                font-size: 1.8em;
            }
            
            .content {
                padding: 20px;
            }
            
            table {
                font-size: 0.9em;
            }
            
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🚀 Workshop AWS - BCRP</h1>
            <p>Aplicación de Prueba - Alta Disponibilidad y Escalabilidad</p>
        </header>
        
        <div class="content">
            <?php if ($mensaje_exito): ?>
                <div class="alert alert-success">
                    ✓ <?php echo $mensaje_exito; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($mensaje_error): ?>
                <div class="alert alert-error">
                    ✗ <?php echo $mensaje_error; ?>
                </div>
            <?php endif; ?>
            
            <div class="instance-info">
                <strong>Información de la Instancia:</strong> 
                <?php 
                $instance_id = file_get_contents('http://169.254.169.254/latest/meta-data/instance-id');
                $availability_zone = file_get_contents('http://169.254.169.254/latest/meta-data/placement/availability-zone');
                echo "ID: " . $instance_id . " | Zona de Disponibilidad: " . $availability_zone;
                ?>
            </div>
            
            <div class="form-section">
                <h2>📝 Enviar Mensaje</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Ingrese su nombre">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required placeholder="correo@ejemplo.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="mensaje">Mensaje:</label>
                        <textarea id="mensaje" name="mensaje" required placeholder="Escriba su mensaje aquí..."></textarea>
                    </div>
                    
                    <button type="submit">Enviar Mensaje</button>
                </form>
            </div>
            
            <div class="table-section">
                <h2>📋 Mensajes Registrados</h2>
                <?php if (isset($result) && $result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Mensaje</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['mensaje']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        No hay mensajes registrados aún. ¡Sé el primero en enviar uno!
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <footer>
            <p>Workshop AWS - Banco Central de Reserva del Perú</p>
            <p>Laboratorio 2.3: Elasticidad y Alta Disponibilidad con ELB, ASG y CloudFormation</p>
        </footer>
    </div>
</body>
</html>
<?php
if (isset($conn)) {
    $conn->close();
}
?>
