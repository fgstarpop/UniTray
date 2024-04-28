import requests

import re
from fastapi import   HTTPException
import hashlib
from Crypto.Cipher import AES
import base64


class QimaoPlugin:
 
    def __init__(self):
        self.site_name = ["qimao.com"]
        self.name = "Qimao Plugin"
        self.description = """Hỗ trợ lấy thông tin truyện và lấy chương truyện trên web: qimao.com"""
        self.author = "daerty5"
        self.active = True
        self.version = "0.1"
        self.url = None
        self.utf = True
        self.urlinfo = "https://api-bc.wtzw.com/api/v5/book/detail?id={bookid}"
        self.urllist = "https://www.qimao.com/api/book/chapter-list?book_id={bookid}"
        self.urlread = "https://api-bc.wtzw.com/chapterId={chapid}&id={bookid}"
        self.urlogbook = "https://www.qimao.com/shuku/{bookid}/"
        self.urlogchap = "https://www.qimao.com/shuku/{bookid}-{chapid}/"
        self.mainurl = None
        self.idweb = "qimao"
        #regex để lấy id từ trên link
        self.regexlink = [
            "/(?P<bookid>\d+)-(?P<chapid>\d+)/",
            "/(?P<bookid>\d+)(?P<chapid>)/",
            ]
       
    def bookinfo(self):
        id = self.getId()
        if id is None:
            return None
        if not id['bookid'] :
            return None
        html_content = self.execute_info(id['bookid'])
        # try:
   
        info = self.getInfo(html_content['data']) # lấy thôn tin truyện
        # info.update({"bookid":id['bookid']})
        info['bookid'] = id['bookid']
        info['linkorigin'] = self.urlogbook.format(bookid=id['bookid'])
        listchap = self.getList(html_content['data'])

        if listchap:
            info.update( listchap) #lấy danh sách truyện
        info['host'] = id['idweb']
    
        return info
        # except: 
        #     raise HTTPException(status_code=400,detail=message(15,1,
        #     "Lỗi khi lấy thông tin truyện qimao"))
            
            
            
    def getId(self):
        url = self.url
        regexs = self.regexlink
        for regex in regexs:
          
         
            numbers = re.search(regex,url)

            if numbers:
                bookid = numbers.group("bookid")
                chapid = numbers.group("chapid")
                return {"bookid":bookid,"chapid":chapid,"idweb":self.idweb}
            

        return None
    
    def getInfo(self,content):
        textintro = str.replace(content['book']['intro'],"\n","<br><br>")
        return {
            'name':content['book']['title'],
            'tacgia':content['book']['author'],
            'img':content['book']['thumb_image_link'],
            'theloai':content['book']['category1_name'] ,
            'gioithieu':textintro,
            'tag': None,
            'hostvip':False,
            'utf-8':False,      
            }
     
    def getList(self,content=None):
        id = self.getId()
        if id is None :
            return None
        if not id['bookid']:
            return None
        if content is None:
            return self.bookinfo()
        else:
          
            response = self.execute_list(id['bookid'])
            contentjson = response
            chaplist = {"listchap":[]}
            try:
                for chap in contentjson['data']['chapter_lists']:
                    chaplist["listchap"].append({"id":chap['id'],
                                    "namechap":chap['title'],
                                    "vip": False,
                                    "linkchap":self.urlogchap.format(bookid= id['bookid'],chapid=chap['id']),
                                    
                                    })
                    
            except:
                return None
        return chaplist

   
    
    def getContent(self):
        id = self.getId()
        if not id['chapid']:
            return None
        url = self.urlread.format(chapid = id['chapid'],bookid=id['bookid'])
        txt = self.execute(url)
        if txt is None:
            return None

        url = self.urllist.format(bookid=id['bookid'])
        response = requests.get(url,timeout=30)
        html_content = response.json()
        chaps = html_content['data']['chapters']
        text = None
        for chap in chaps:
            if chap['id'] == id['chapid']:
                text = chap
                break

        try:
            linkchap = self.urlogchap.format(bookid= id['bookid'],chapid=id['chapid'])
            id.update({"bookname":None,'name':text['title'],
                    "linkchap":linkchap,"content":txt, "host":id['idweb']})
            return id
        except:
            return None
        
    def execute(self,url):
        try:
            nurl = url.replace('https://api-bc.wtzw.com/', '')
            sign = hashlib.md5(f'{nurl.replace("&", "")}d3dGiJc651gSQ8w1'.encode()).hexdigest()
            headers = {
                "platform": "android",
                "app-version": "71900",
                'application-id': 'com.kmxs.reader',
                'sign': hashlib.md5("app-version=71900application-id=com.kmxs.readerplatform=androidd3dGiJc651gSQ8w1".encode()).hexdigest(),
                'user-agent': 'webviewversion/0'
            }
            response = requests.get(f'https://api-ks.wtzw.com/api/v1/chapter/content?{nurl}&sign={sign}', headers=headers)
            data = response.json()
            txt = base64.b64decode(data['data']['content'])
            iv = txt[:16]
            content = self.decrypt(txt[16:], iv).strip()
            return content
        except:
            return None
        
    def decrypt(self,data, iv):
        key = bytes.fromhex('32343263636238323330643730396531')
        cipher = AES.new(key, AES.MODE_CBC, iv)
        decrypted = cipher.decrypt(data)
        return decrypted.decode('utf-8')
    
    def execute_info(self,bookid):
        
        sign = hashlib.md5(f"id={bookid}d3dGiJc651gSQ8w1".encode()).hexdigest()

        headers = {
            "platform": "android",
            "app-version": "71900",
            'application-id': 'com.kmxs.reader',
            'sign': hashlib.md5("app-version=71900application-id=com.kmxs.readerplatform=androidd3dGiJc651gSQ8w1".encode()).hexdigest(),
            'user-agent': 'webviewversion/0'
        }

        response = requests.get(f"https://api-bc.wtzw.com/api/v5/book/detail?id={bookid}&sign={sign}", headers=headers)
        data = response.json()
       
        return data
    def execute_list(self,bookid):

        sign = hashlib.md5(f'id={bookid}d3dGiJc651gSQ8w1'.encode()).hexdigest()
        headers = {
            "platform": "android",
            "app-version": "71900",
            "application-id": "com.kmxs.reader",
            "sign": hashlib.md5("app-version=71900application-id=com.kmxs.readerplatform=androidd3dGiJc651gSQ8w1".encode()).hexdigest(),
            "user-agent": "webviewversion/0"
        }
        response = requests.get(f"https://api-ks.wtzw.com/api/v1/chapter/chapter-list?id={bookid}&sign={sign}", headers=headers)
        data = response.json()
       
        return data
