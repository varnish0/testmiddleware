# Documentación Técnica: Instalación y Configuración de Servidor FTP (vsftpd) en Linux

## 1. Datos del Entorno
- **Servidor FTP (SO):** Kali Linux (GNU/Linux 7.0 / Debian-based)
- **IP Servidor:** `172.18.3.154`
- **Software FTP:** `vsftpd` (v3.0.5)
- **Cliente de pruebas:** macOS (`172.18.2.53`)
- **Usuario de prueba:** `ftpuser`

---

## 2. Instalación de vsftpd

Actualización de índices e instalación del paquete `vsftpd`:

```bash
sudo apt-get update
sudo apt-get install -y vsftpd
```

---

## 3. Configuración del Servidor (`/etc/vsftpd.conf`)

Respaldo del fichero original:
```bash
sudo cp /etc/vsftpd.conf /etc/vsftpd.conf.bak
```

Fichero de configuración aplicado (`/etc/vsftpd.conf`):

```ini
# Configuración de red y escucha
listen=YES
listen_ipv6=NO

# Deshabilitar acceso anónimo
anonymous_enable=NO

# Habilitar acceso de usuarios locales del sistema
local_enable=YES

# Habilitar permisos de escritura (subida y borrado de archivos)
write_enable=YES
local_umask=022

# Mensajes informativos y hora local
dirmessage_enable=YES
use_localtime=YES

# Registro y auditoría de transferencias
xferlog_enable=YES
xferlog_file=/var/log/vsftpd.log
xferlog_std_format=YES
connect_from_port_20=YES

# Seguridad: Enjaulamiento (chroot) de usuarios en su directorio raíz
chroot_local_user=YES
allow_writeable_chroot=YES

# Directorio seguro y módulo PAM
secure_chroot_dir=/run/vsftpd/empty
pam_service_name=vsftpd

# Rango de puertos y dirección para modo pasivo (tráfico a través de red)
pasv_enable=YES
pasv_min_port=40000
pasv_max_port=40100
pasv_address=172.18.3.154

# Desactivar cifrado forzado (FTP estándar en plano)
ssl_enable=NO
```

---

## 4. Creación del Usuario Específico de Pruebas

Creación del usuario `ftpuser` con shell `/bin/bash` y directorio home asignado:

```bash
# Creación del usuario y asignación de contraseña
sudo useradd -m -s /bin/bash ftpuser
echo "ftpuser:ftpuser123" | sudo chpasswd

# Asegurar permisos correctos sobre el directorio raíz del usuario
sudo chown -R ftpuser:ftpuser /home/ftpuser
sudo chmod 755 /home/ftpuser
```

---

## 5. Control del Servicio

Habilitación del servicio en el arranque e inicio:

```bash
sudo systemctl enable vsftpd
sudo systemctl restart vsftpd
sudo systemctl status vsftpd
```

Verificación del socket escuchando en el puerto 21/TCP:

```bash
sudo ss -tulpn | grep :21
# tcp   LISTEN 0      32           0.0.0.0:21        0.0.0.0:*    users:(("vsftpd",pid=12823,fd=3))
```

---

## 6. Pruebas de Funcionamiento

Todas las pruebas se ejecutaron desde el equipo cliente (`172.18.2.53`).

### 6.1. Prueba de Bloqueo de Acceso Anónimo

Comando:
```bash
curl -v --ftp-pasv ftp://172.18.3.154/
```
Salida obtenida:
```
* Connected to 172.18.3.154 (172.18.3.154) port 21
< 220 (vsFTPd 3.0.5)
> USER anonymous
< 331 Please specify the password.
> PASS ftp@example.com
< 530 Login incorrect.
* Access denied: 530
* Closing connection
curl: (67) Access denied: 530
```
**Resultado:** Acceso anónimo bloqueado correctamente con código HTTP/FTP 530.

---

### 6.2. Autenticación y Listado con Usuario de Prueba

Comando:
```bash
curl -v --user "ftpuser:ftpuser123" --ftp-pasv ftp://172.18.3.154/
```
Salida obtenida:
```
< 220 (vsFTPd 3.0.5)
> USER ftpuser
< 331 Please specify the password.
> PASS ftpuser123
< 230 Login successful.
> PWD
< 257 "/" is the current directory
> EPSV
< 229 Entering Extended Passive Mode (|||40047|)
> LIST
< 150 Here comes the directory listing.
-rw-r--r--    1 1001     1001           84 Nov 05 13:19 archivo_descarga.txt
< 226 Directory send OK.
```
**Resultado:** Autenticación exitosa y negociación correcta en modo pasivo.

---

### 6.3. Prueba de Descarga (Download)

Descarga del archivo `archivo_descarga.txt` desde el servidor hacia el cliente:

```bash
curl --user "ftpuser:ftpuser123" --ftp-pasv "ftp://172.18.3.154/archivo_descarga.txt" -o archivo_descarga.txt
```
Traza de transferencia:
```
> RETR archivo_descarga.txt
< 150 Opening BINARY mode data connection for archivo_descarga.txt (84 bytes).
< 226 Transfer complete.
100    84  100    84    0     0   1262      0 --:--:-- --:--:-- --:--:--  1272
```
Contenido verificado en cliente:
```bash
cat archivo_descarga.txt
# Este es un archivo de prueba para verificar la DESCARGA desde el servidor FTP Kali.
```
**Resultado:** Descarga completada con éxito.

---

### 6.4. Prueba de Subida (Upload)

Subida del archivo local `archivo_subida_mac.txt` al directorio remoto del servidor:

```bash
curl --user "ftpuser:ftpuser123" --ftp-pasv -T archivo_subida_mac.txt "ftp://172.18.3.154/archivo_subida_mac.txt"
```
Traza de transferencia:
```
> STOR archivo_subida_mac.txt
< 150 Ok to send data.
< 226 Transfer complete.
100   112    0     0  100   112      0   1717 --:--:-- --:--:-- --:--:--  1723
```
Verificación en el servidor Kali (`/home/ftpuser`):
```bash
ls -la /home/ftpuser/archivo_subida_mac.txt
# -rw-r--r-- 1 ftpuser ftpuser 112 nov  5 13:20 archivo_subida_mac.txt
```
**Resultado:** Subida completada e integrada en el sistema de ficheros del servidor.

---

### 6.5. Auditoría en Logs del Servidor (`/var/log/vsftpd.log`)

Registros generados en el servidor durante las dos operaciones:

```
Thu Nov  5 13:19:39 2026 1 172.18.2.53 84 /archivo_descarga.txt b _ o r ftpuser ftp 0 * c
Thu Nov  5 13:20:04 2026 1 172.18.2.53 112 /archivo_subida_mac.txt b _ i r ftpuser ftp 0 * c
```
- Operación `o` (outgoing / descarga): archivo `archivo_descarga.txt` enviado al cliente `172.18.2.53`.
- Operación `i` (incoming / subida): archivo `archivo_subida_mac.txt` recibido desde el cliente `172.18.2.53`.
