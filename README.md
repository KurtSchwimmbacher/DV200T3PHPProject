![ClearView Header Image](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/vagabond_logo.png)

- - - -

# About Forest Tactics

Forest Tactics is a QnA dashboard created for Root boardgame players to post questions related to the game for other players to answer and vote on.

### Built With
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://www.w3.org/html/)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://www.w3.org/Style/CSS/Overview.en.html)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Jquery](https://img.shields.io/badge/JQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white)](https://jquery.com/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com/)
[![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)](https://apachefriends.org/)


## How To Install
Prerequisites
```
XAMPP (or any similar LAMP/WAMP/MAMP stack)
```
```
PHP (version X.X or higher)
```
```
MySQL (version X.X or higher)
```
```
A code editor (e.g., Visual Studio Code, Sublime Text)
```

Step 1 clone the repo:
```
git clone https://github.com/KurtSchwimmbacher/DV200T3PHPProject
```
Step 2: Move Project to XAMPP Directory
```
On Windows: C:\xampp\htdocs\
```
```
On Mac: /Applications/XAMPP/htdocs/
```
```
On Linux: /opt/lampp/htdocs/
```

Step 3: Start Apache and MySQL
```
Open XAMPP Control Panel.
```
```
Start Apache and MySQL.
```
Step 4: Create the Database
```
Open your browser and navigate to phpMyAdmin.
```
```
Click on Databases at the top.
```
```
Create a new database with the name: your_database_name.
```
Step 5: Import the Database
```
In phpMyAdmin, select your newly created database.
```
```
Click on the Import tab.
```
```
Click Choose File and select the SQL file from the project directory (usually named database.sql or similar).
```
```
Click Go to import the database structure and data.
```
Step 6: Update Environment Configuration
```
Locate the configuration file (e.g., config.php or .env) and update the following values with your local setup:
```
```
// Example configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'root'); // or your MySQL username
define('DB_PASS', ''); // or your MySQL password
```

Step 7: Access the Project
```
Open your browser.
Go to http://localhost/your-project-folder/.
```


## Features

| Page                  | Description                                         |
| --------------------- | --------------------------------------------------  |
| Login Page            | - Allows all users to create a profile              |
|                       | - Provides login functionality for registered users |
| Home Page             | - Allows users to see the latest posts              |
|                       | - Allows users to see the top liked posts |
|                       | - Allows users search for posts, filter by tags, and sort questions |
| Feed Page             | - Allows users to see all the latest posts and questions from other users |
| Create Post Page      | - Allows users to create new posts               |
| Individual Post Page  | - Allows students to view the whole post           |
|                       | - Provides the ability to comment and vote on posts |
| Account Activity Page  | - Provides users with their profile information, such as total posts |
|                       | - Allows users to see their past questions and answers |
|                       | - Provides users with a log of activity happening on their posts |
|                       | - Allows users to change their profile pictures |

## The Idea

The idea was to create a centralised hub where users can post questions regarding help, strategy, or advice regarding the boardgame Root and have other users engage with the post to either help them as well as vote on questions.

## UI Design

### Home Page
![Home Page UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Home-Design.png)

### Create Post Page
![Create Post UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Create-Post-Design.png)

### Update a Post Page
![Update Post UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Update-Post-Design.png)

### Feed Page
![Feed Page UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Feed-Design.png)

### Profile / Account Activity Page
![Profile Page UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Account-Activity-Design.png)
![Profile Page 2 UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Account-Activity-Design-2.png)

### Admin Approve Page
![Admin Approve UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Admin-Approve-Design.png)

### Single Question Page
![Single Question UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Single-Question-Design.png)
![Single Question UI Design 2](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Single-Question-Design-2.png)

### Login Page
![Login UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Login-Design.png)

### Sign Up Page
![Sign Up UI Design](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/SignUp-Design.png)


## Development Process

### Highlights
* Highlights of the website is the stylised UI and the functionality that gives the user a pleasant user experience.
* The website is easy to understand and navigate.


### Challenges
* The integration of votes, comments and answers on posts was challenging to implement in a cohesive and intuitive way.
* Building in redundancy to the functionality of the website to ensure a smooth user experience.

## Future Implementations

* Allow Users to view other user Profiles
* Add super admin functionality that allows Admins to post announcements to the home page
* Allow users to create new tags or subcommunites for more specific questions

## Mockups

### Account Mockup
![Account Mockup](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Mockup-Account.png)

### Feed Page Mockup
![Account Mockup](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Mockup-Feed.png)

### Individual Post Page Mockup
![Individual Post Mockup](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Mockup-Single-Post.png)

### Home Page Mockup
![Home Page Mockup](https://github.com/KurtSchwimmbacher/DV200T3PHPProject/blob/main/assets/ReadMeAssets/Mockup-Home.png)

## Demonstration
### The video has been split into two section: for the website and the code, due to the length of the code discussion.

### Whole Video
https://drive.google.com/file/d/11QrFC_1GST2Ld0x8lPuUsIYfImTeOKR8/view?usp=sharing

### Website Video
https://drive.google.com/file/d/1ckGuG22N9LyvHymzshEvRQA5Tg6XTelD/view?usp=sharing

### Code Video
https://drive.google.com/file/d/1tDYIP3_Q2OBgRXUBlawq1oRaDuwcoHcd/view?usp=sharing

### License
[MIT](LICENSE) © Kurt Schwimmbacher

### Author
Kurt Schwimmbacher - 231002@virtualwindow.co.za

### Contribution
In order to contribute to this project, clone the project according the instructions layed out above and create a new branch.
