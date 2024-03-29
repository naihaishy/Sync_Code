# -*- coding:utf-8 -*-
# @Time : 2019/6/2 21:20
# @Author : naihai

"""
代码同步 客户端
"""
import time
import win32api
import win32clipboard as wc

import requests
import win32con

USER_NAME = "xxx"
USER_KEY = "12345678"


def upload(code):
    """
    将代码同步到服务器
    :param code:
    :return:
    """
    data = {
        'user_name': USER_NAME,
        'user_key': USER_KEY,
        'code': code
    }
    print(data)
    response = requests.post(url="xxxx/upload", data=data)
    print(response)


def get_code():
    """
    从剪切板获取代码
    :return:
    """
    wc.OpenClipboard()
    copy_text = wc.GetClipboardData(win32con.CF_TEXT)
    wc.CloseClipboard()
    return copy_text.decode('gbk')


def main():
    """
    检测按键状态 当同时按下 ctrl + als + numpad0时执行同步
    首先将拷贝代码到剪切板
    :return:
    """
    last_press_time = time.time()
    while 1:
        if win32api.GetAsyncKeyState(0x11) and \
                win32api.GetAsyncKeyState(0x12) and \
                win32api.GetAsyncKeyState(0x4F):
            if time.time() - last_press_time >= 5:
                code = get_code()
                upload(code)
                last_press_time = time.time()
        time.sleep(.300)


if __name__ == '__main__':
    main()
