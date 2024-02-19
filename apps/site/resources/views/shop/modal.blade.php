
<style>
    .cus-modal {
        padding: 6px 60px;
        border: 1px solid #ccc;
        border-radius: 3px !important;
        cursor: pointer;
        margin-left: 25px !important;
    }

    .cus-view {
        position: absolute;
        background: rgba(0, 0, 0, 0.5);
        width: 100%;
        height: 100%;
        top:0;
        bottom: 0;
        z-index: 10000;
        display: none;
        position: fixed;
    }

    .cus-view-it {
        border: 1px solid #ccc;
        width: 436px;
        height: 271px;
        background: #fff;
        border-radius: 5px;
        border: 1px solid #ccc;
        padding: 20px
    }

    .cus-view-mo {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .cus-view-it-item {
        float: left;
        width: 33%;
        margin-top: 10px
    }

    .cus-view-it-header {
        margin-top: 20px
    }

    .custom-checkbox {
  display: none;
}

.checkbox-label {
  position: relative;
  cursor: pointer;
  padding-left: 30px;
}

.checkbox-label:before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  width: 20px;
  height: 20px;
  border: none;
  border: 1px solid #ccc;
  border-radius: 50%;
}

.custom-checkbox:checked + .checkbox-label:before {
  background-color: green;
}

.cus-view-it-footer {
    margin-top: 40px;
    display: flex;
    justify-content: space-between;
}

#find-sou {
    width: 30%;
    text-align: center;
    background: #1cb15c;
    padding: 5px;
    color: #fff;
    cursor: pointer;
}

#find-all-sou {
    width: 30%;
    text-align: center;
    background: #1cb15c;
    padding: 5px;
    color: #fff;
    cursor: pointer;
}

#close-sou {
    width: 30%;
    text-align: center;
    background: #ccc;
    padding: 5px;
    color: #fff;
    cursor: pointer;
}
@media only screen and (max-width: 600px) {
        .cus-modal {
            margin-left: 10px !important;
            margin-top: 10px;
            margin-bottom: 20px;
            width: 70%;
            text-align: center;
        }
    }
</style>

<div class="cus-modal" id="cus-modal">
    <div>
        <span style="margin-right: 5px" id="text-me">Tất cả</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
        </svg>
    </div>
</div>