# -*- coding:utf-8 -*-
# @Time : 2019/6/2 21:37
# @Author : naihai

"""
代码同步 服务器端
"""
import logging
import time
from flask import Flask, jsonify
from flask import request
import pymysql

app = Flask(__name__)


class MySql(object):
    def __init__(self):
        self.conn = pymysql.connect(host='127.0.0.1', user='xxx',
                                    password='xxxx', db='xxx',
                                    charset='utf8', cursorclass=pymysql.cursors.DictCursor)
        self.cursor = self.conn.cursor()

    def sync(self, data):
        """同步代码"""
        insert_sql = "INSERT into sync_code(user_name, sync_time, code) VALUES (%s, %s, %s)"
        insert_val = (data['user_name'], time.time(), data['code'].strip(""))
        try:
            result = self.cursor.execute(insert_sql, insert_val)
            if result:
                logging.info("sync code succeed of user {}".format(data['user_name']))
            else:
                logging.info("sync code failed of user {}".format(data['user_name']))
        except Exception as e:
            logging.warning("db operation failed[sync ]: {0}".format(repr(e)))

    def __del__(self):
        # 释放资源
        self.cursor.close()
        self.conn.close()


@app.route('/upload', methods=['POST'])
def upload():
    """上传代码数据"""
    data = dict()
    if request.method == 'POST':
        data['user_name'] = request.form['user_name']
        data['code'] = request.form['code']
        # 将code写入数据库
        mysql = MySql()
        mysql.sync(data)
    res = {'code': 0, 'msg': 'ok'}
    return jsonify(res)


if __name__ == '__main__':
    app.debug = True
    app.run(host='0.0.0.0', port=8888)
