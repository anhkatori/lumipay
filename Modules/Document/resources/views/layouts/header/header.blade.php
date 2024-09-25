<style>
    body {
        font-family: Arial, sans-serif;
        margin: 40px;
    }

    h1 {
        font-size: 36px;
        color: #333;
    }

    .component:not(:first-child) {
        padding-top: 60px;
    }

    p {
        font-size: 18px;
        color: #666;
    }

    h2 {
        font-size: 24px;
        color: #333;
        margin-top: 40px;
    }

    .step {
        margin-top: 20px;
    }

    .step-number {
        font-size: 48px;
        color: #e0e0e0;
        float: left;
        margin-right: 10px;
    }

    .step-content {
        overflow: hidden;
    }

    .step-title {
        font-size: 30px;
        color: #248eff;
        margin-bottom: 15px;
    }

    .step-description {
        font-size: 16px;
        color: #666;
    }

    .step-description strong {
        color: #333;
    }

    .step-description a {
        color: #007bff;
        text-decoration: none;
    }

    .step-description a:hover {
        text-decoration: underline;
    }

    .code-block {
        background-color: #f8f8f8;
        border: 1px solid #e1e1e1;
        padding: 15px;
        margin: 20px 0;
        position: relative;
    }

    .code-block pre {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
        font-family: "Courier New", Courier, monospace;
    }

    .copy-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #f8f8f8;
        border: 1px solid #e1e1e1;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 14px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .endpoint,
    .method {
        margin-top: 10px;
    }

    .sidebar {
        width: 18%;
        background-color: #fff;
        height: 100vh;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        box-sizing: border-box;
        position: fixed;
        top: 0;
        left: 0;
        float: left;
    }

    .logo {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo img {
        width: 150px;
    }

    .nav-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .nav-links li {
        margin-bottom: 10px;
    }

    .nav-links a {
        text-decoration: none;
        color: #007bff;
        font-size: 14px;
    }

    .nav-links a:hover {
        text-decoration: underline;
    }

    .menu {
        margin-top: 20px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border-bottom: 1px solid #e0e0e0;
        color: #333;
        text-decoration: none;
        font-size: 14px;
    }

    .menu-item.active {
        background-color: #ccc;
        color: #333;
    }

    .menu-item:hover {
        background-color: #f0f0f0;
    }

    .menu-item i {
        margin-right: 10px;
        color: #007bff;
    }

    .footer {
        position: absolute;
        bottom: 20px;
        width: 260px;
        text-align: center;
        font-size: 12px;
        color: #999;
    }

    .footer a {
        color: #007bff;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }

    .content {
        float: right;
        width: 80%;
        margin-bottom: 350px;
    }


    .menu-mobile-toggle {
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        border: 1px solid gray;
        color: black;
    }

    .menu-mobile-list {
        display: none;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .menu-mobile-open {
        display: block;
    }

    .menu-item-mobile {
        display: block;
        padding: 10px;
        border-bottom: 1px solid #ccc;
        text-decoration: none;
        color: black;
    }

    .menu-item-mobile:hover {
        background-color: #f0f0f0;
    }
    .redirect-response{
        display:flex
    }

    @media only screen and (max-width: 768px) {
        html {
            width: 100%
        }

        body {
            margin: 20px;
        }

        .sidebar {
            display: none;
        }

        .content {
            width: 100%;
            margin-bottom: 100px
        }

        .code-block pre {
            white-space: pre-wrap;
            word-break: break-all;
            margin-top: 35px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table-responsive td {
            white-space: nowrap;
        }
    }
</style>