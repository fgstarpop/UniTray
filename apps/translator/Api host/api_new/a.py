from fastapi import FastAPI,Form,Body
from starlette.requests import Request
from starlette.responses import HTMLResponse, PlainTextResponse
import uvicorn
import re
from fastapi.templating import Jinja2Templates
from pydantic import HttpUrl
import json
import requests,hashlib
import os
import os.path
import base64
import js2py
from Cryptodome.Cipher import AES
app = FastAPI() 
template = Jinja2Templates(directory='html')

def getContentStv(host, bookid, cid,style):
    header = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0',

        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest',
        'Referer' : "http://sangtacvietfpt.com/truyen/{host}/{style}/{bookid}/{cid}/"
    }
    header2 = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:108.0) Gecko/20100101 Firefox/108.0',

      
    }
    cookies_set = {
        'PHPSESSID': '0c0b7u6db2m80qtald2of2vcf4',
        'transmode':'chinese'      
    }
    url = f"http://sangtacviet.com/index.php?ngmar=readc&sajax=readchapter&bookid={bookid}&h={host}&c2=&c={cid}&sty={style}&exts="
    
    url2 = f"http://sangtacviet.com/truyen/{host}/{style}/{bookid}/{cid}/"

    response = requests.get(url2, headers=header2,
                            cookies=cookies_set, timeout=30)

    cookies_set['_ac'] = response.cookies.get('_ac')
    cookies_set['arouting'] = response.cookies.get('arouting')

    response2 = requests.post(url, cookies=cookies_set,
                             headers=header, timeout=30)
    try:
        data = json.loads(response2.text.replace(u'\ufeff', ''))
    except:
        data = json.loads(response2.text)
        
   
    if data['code'] == "0":
        return data
    return None

def getInfoTruyenStv(host,idBook,style):
	header = {
		"Referer":	f"http://sangtacviet.com/truyen/{host}/{style}/{idBook}/"
	}
	urlAjax = f"http://sangtacviet.com/index.php?ngmar=chapterlist&h={host}&bookid={idBook}&sajax=getchapterlist"
	urlInfo = f"http://sangtacviet.com/truyen/{host}/{style}/{idBook}/"
	getInfo = requests.get(urlInfo)
	dataInfo = getInfo.text
	regexInfo = r"var bookinfo =(?P<info>.*?);.*?<div id=\"book-sumary\" style=\"font-size: 16px\" class=\"blk-body\">(?P<gioiThieu>.*?)</div>"
	if regexInfo is None:
		return None
	
	infoSearch = re.search(regexInfo,dataInfo,re.DOTALL)
	info = json.loads(infoSearch['info'])
	try:
		fetch = requests.get(urlAjax,headers=header)
		text = fetch.text.encode().decode('utf-8-sig')
		jsondecode = json.loads(text)
		enckey = base64.b64decode(jsondecode['enckey']).decode('utf-8')
		enckey = enckey.replace('eval','temp = ')
		evalJs = js2py.eval_js(enckey)

		regex = "k='(?P<key>.*?)'.*?var s='(?P<string>.*?)'.*?_4kqme4=\[(?P<iv>.*?)\];"
		sRegex = re.search(regex,evalJs)
		listChuong = []
		if sRegex is not None:
			splitIv = sRegex['iv'].split(',')
			keyAes = sRegex['key']+"gsj9"
			string= base64.b64decode(sRegex['string']+jsondecode['data'])
			iv = b"\x31\x32\x33\x34\x35\x36\x37\x38\x39\x30\x31\x32\x33\x34\x35\x36"
			
			
			decrypt = AES.new(keyAes.encode('utf-8'),AES.MODE_CBC,iv)
			data =decrypt.decrypt(string).decode('utf-8')
			splitChap = data.split('-//-')
			for chap in splitChap:
				chaps = chap.split('-/-')
				listChuong.append({
					"namechap":chaps[2],
					"id":chaps[1],
					"linkchap":f"http://sangtacviet.com/truyen/{host}/{style}/{idBook}/{chaps[1]}/",
					"vip":False,
				})
			
	
	except:
		return None
	
	

	
	infoReturn = {
		'name':info['name'],
		'tacgia':info['author'],
		'img':info['thumb'],
		'theloai':None,
		'gioithieu':infoSearch['gioiThieu'],
		'tag': [],
		'hostvip':False,
		'bookid':idBook,
		'host':f"stv-{host}",
		'linkorigin':f"http://sangtacviet.com/truyen/{host}/{style}/{idBook}/",
		'utf-8':True,
		'listchap':listChuong
    }
	
	# infoReturn['listChuong'] = listChuong
		



	return infoReturn

def check_host(url):
    if "fanqienovel.com" in url:
        if "fanqienovel.com/page" in url:
            url_test = re.search("fanqienovel.com/page/(?P<id>.\d+).*?",url,re.S)
            return {"bookid":url_test['id'],
                    "host":'fanqie',
                    "chapid": None,}
        elif "fanqienovel.com/reader" in url:
            try:
                url_test = re.search("fanqienovel.com/reader/(?P<id>.\d+).*?",url,re.S)
                data_chap_read = html_get(f"https://novel.snssdk.com/api/novel/reader/full/v1/?item_id={url_test['id']}")
                data_chap_read = json.loads(data_chap_read)
                read_info = data_chap_read['data']
            
                bookid = read_info['novel_data']['book_id']
                
                return {"bookid":bookid,
                        "host":'fanqie',
                        "chapid": url_test['id'],}
            except:
                pass
    with open('host.json', 'r') as token:
        regex = json.loads(token.read())
        token.close()
    for host in regex:
        for regx in regex[host]:      
            linkinfo = re.search(regx,url)
            if linkinfo is not None:
                
                try :
                    if linkinfo[2] !='':
                        return {"chapid":linkinfo[2],
                                            "bookid":linkinfo[1],
                                            "host":host}
                    else:
                        return {"chapid": None,
                                        "bookid":linkinfo[1],
                                        "host":host}
                    
                except :
                    return {"chapid": None,
                                        "bookid":linkinfo[1],
                                        "host":host}
    return  None   
def get_fanqie(id,mode=0):
    if mode == 0:
        
        # try:
        data_book_info = html_get(f"https://novel.snssdk.com/api/novel/book/directory/list/v1?book_id={id}")
        data_book_info = json.loads(data_book_info)
        data_item = data_book_info['data']['item_list']
        list_range = range(0,len(data_item),49)
        list_post = []
        list_chap = []
        for n in list_range:  
            list_post.append(data_item[n:n+49])
            
        for c in list_post:
        
            res = requests.post(f"https://fanqienovel.com/api/reader/directory/list",json={"itemIds":c})
            chapter = res.json()
            for chap in chapter['data']:
                list_chap.append({"id":chap['itemId'],'vip':False,"namechap":chap['title'],"linkchap":f"https://fanqienovel.com/reader/{chap['itemId']}"})
       
        # for chap in data_chap_list['data']:
        #     list_chap.append({"id":chap['itemId'],'vip':False,"namechap":chap['title'],"linkchap":f"https://fanqienovel.com/reader/{chap['itemId']}"})
       
        # print(list_chap)
        # return None
        # print(data_chap_list)
        # f = open(f"{id}.txt", 'w', encoding='utf-8')
        # f.write(data_chap_list)
        # f.close()
        # res = '<div class="chapter-item"><a href="/reader/(?P<cid>\d+)" class="chapter-item-title" target="_blank">(?P<title>.*?)</a></div>'
        # chaplist = re.finditer(res,data_chap_list,re.S)
        
        
        info = data_book_info['data']['book_info']
        bookname = info['book_name']
        author = info['author']
        img = info['thumb_url']
        description= info['abstract']
        category = info['category']
    
    
        tags = []
        for tag in info['category_tags']:
            
            tags.append(tag['category_name'])
    
        bookinfo = {
                    'name':bookname,
                    'tacgia':author,
                    'img':img,
                    'theloai':category,
                    'gioithieu':description,
                    'tag': tags,
                    'hostvip':True,
                    'bookid':f"{id}",
                    'host':"fanqie",
                    'linkorigin':f"https://fanqienovel.com/page/{id}",
                    'utf-8':False,
                    'listchap':list_chap
                }
        return bookinfo
        # except:
        #     None
    elif mode == 1:
        try:
            data_chap_read = html_get(f"https://novel.snssdk.com/api/novel/reader/full/v1/?item_id={id}")
            data_chap_read = json.loads(data_chap_read)
            read_info = data_chap_read['data']
            content = read_info['content']
            bookid = read_info['novel_data']['book_id']
            bookname = read_info['novel_data']['book_name']
            chapname = read_info['novel_data']['title']
            readreturn ={
                    'bookid':bookid,
                    'chapid':f"{id}",
                    'bookname':bookname,
                    'name':chapname,
                    'host':"fanqie",
                    'content':content
                }
            return readreturn  
        except:
            return None 
        
        
        
        
def get_info(url):
    if "fanqienovel.com/page" in url:
        url_test = re.search("fanqienovel.com/page/(?P<id>.\d+)",url,re.S)
        return get_fanqie(url_test['id'])
    elif "fanqienovel.com/reader" in url:
        url_test = re.search("fanqienovel.com/reader/(?P<id>.\d+)",url,re.S)
        return get_fanqie(url_test['id'],1)
    check_test =  check_host(url)
    if check_test is None:
        return None
    else:
        with open('host/'+check_test['host']+'.json', 'r', encoding='utf-8') as token:
            host = json.loads(token.read())
            token.close()
        if check_test['chapid'] is None:
            if  not host['utf-8']:
                datahost =  html_get(host['link_book_info'].format(id=check_test['bookid']),True)
            else:
                datahost =  html_get(host['link_book_info'].format(id=check_test['bookid']))
            content = re.search(host['reg_book'],datahost,re.S)
            if content is None:
                return None
            else:
            
                chaplist1 = get_list(check_test['bookid'],host,datahost)
                if chaplist1 is not None:
                    tag = None
                    try:
                        img = content['thumb']
                    except:
                        img = None
                    if host['tag'] is not None:
                        tag = re.findall(host['tag'] ,datahost,re.S)
                    if host['repimg'] is not None:
                        img = host['repimg']+content['thumb']
                    
                        
                    bookinfo = {
                        'name':content['bookname'],
                        'tacgia':content['author'],
                        'img':img,
                        'theloai':content['category'],
                        'gioithieu':content['description'],
                        'tag': tag,
                        'hostvip':host['vip'],
                        'bookid':check_test['bookid'],
                        'host':check_test['host'],
                        'linkorigin':host['link_book_info'].format(id=check_test['bookid']),
                        'utf-8':host['utf-8'],
                        'listchap':chaplist1
                    }
                    return bookinfo
                else:
                    return None
        else:
    
            if  not host['utf-8']:
                dataread =  html_get(host['link_chap_read'].format(id=check_test['bookid'],c=check_test['chapid']),True)
            else:
                dataread = html_get(host['link_chap_read'].format(id=check_test['bookid'],c=check_test['chapid']))
            chapread = re.search(host['reg_chap_read'],dataread,re.S)
            if chapread is not None:
                contentchap=chapread['content']
                if check_test['host']=='tadu':
                    contentchap = html_get(chapread['content']).replace("callback({content:'",'').replace("'})","")
                readreturn ={
                    'bookid':check_test['bookid'],
                    'chapid':check_test['chapid'],
                    'bookname':chapread['bookname'],
                    'name':chapread['chaptername'],
                    'host':check_test['host'],
                    'content':contentchap
                }
                return readreturn
            else:
                return None
                    
        

def get_list(bookid,hostinfo,html):
    if hostinfo['reload']:
        datachap =  html_get(hostinfo['link_chap_list'].format(id=bookid))
    else:
        datachap = html
    chaplist = re.finditer(hostinfo['reg_chap_list'],datachap,re.S)
    
 
    if chaplist is not None:
        if hostinfo['sort']:
            chaplist = list(chaplist)
            chaplist.reverse()
        listchap = []
        for chap in chaplist:
            if hostinfo['repread'] is not None:
                link = hostinfo['repread'].format(id=bookid,c=chap['cid'] )
            else:
                link = hostinfo['link_chap_read'].format(id=bookid,c=chap['cid'] )
            try:
                if hostinfo['vip']:               
                    if chap['vip'] is None:
                        listchap.append({"id":chap['cid'] ,'vip':False,"namechap":chap['title'],"linkchap":link})       
                else:
                    listchap.append({"id":chap['cid'],'vip':False,"namechap":chap['title'],"linkchap":link})                      
            except:
        
                listchap.append({"id":chap['cid'],'vip':False,"namechap":chap['title'],"linkchap":link})
        
        return listchap
    else:
        return None
    
def html_get(url,checkutf=None):
    header = {
        'User-Agent':'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:94.0) Gecko/20100101 Firefox/94.0'
        
    }
    if checkutf is None:
        response = requests.get(url,headers=header,timeout=30)
    else:
        response = requests.get(url,headers=header,timeout=30)
        response.encoding = 'GB18030'
    return  response.text


    
@app.get('/listfl',response_class=HTMLResponse)
def getfaloo(bookid):
    tor = dict(http='socks5://127.0.0.1:9050',
                https='socks5://127.0.0.1:9050')
    header = {
        "User-Agent":"Mozilla/5.0 (Windows NT 10.0; rv:91.0) Gecko/20100101 Firefox/91.0"
              }
    sign = hashlib.md5(("tengfeikk&"+str(bookid)+"&20191011#tengfeikk#111").encode('utf-8')).hexdigest()
    try:
        data = requests.get(f"http://open.feilu.cc/MiGu/getChapterList/?appid=tengfeikk&bookId={bookid}&sign={sign}",proxies=tor,headers=header,timeout=30).text
        if "本书没有授权" in data:
            return {"status":"error","code":1,"data":"Truyện Không Hỗ Trợ"}
        data = json.loads(data)
        datachap = data['data']
        datachap.sort(key=lambda d: (int(d['volumeId']), int(d['sortNo'])))
    except:
        return {"status":"error","code":2,"data":"không lấy được danh sách chương"}
    text = ''
    if os.path.exists(str(bookid)) :
        pass
    else:
        os.mkdir(str(bookid))
    text =''
    for chapid in datachap:
        text += f"{bookid}-{chapid['id']}-{chapid['name']}<br>"
        
    return text

@app.get('/chapfaloo/{bookid}/{chapid}',response_class=HTMLResponse)
def getchapfaloo(bookid, chapid):
    bookid = str(bookid)
    chapid = str(chapid)
    tor = dict(http='socks5://127.0.0.1:9050',
                https='socks5://127.0.0.1:9050')
    header = {
        "User-Agent":"Mozilla/5.0 (Windows NT 10.0; rv:91.0) Gecko/20100101 Firefox/91.0"
              }
    if os.path.exists(bookid) :
        pass
    else:
        os.mkdir(bookid)
    if os.path.exists(f"{bookid}/{chapid}.txt"):
        datatext = ''
        with open(f"{bookid}/{chapid}.txt", 'r', encoding='utf-8') as f:
            datatext = f.read()
            return datatext.replace("\n","<br>")
    sign2 = hashlib.md5((f"tengfeikk&{bookid}&{chapid}&20191011#tengfeikk#111").encode('utf-8')).hexdigest()
    try:
        datachapter3 = requests.get(f"http://open.feilu.cc/MiGu/getChapterInfo/?appid=tengfeikk&bookId={bookid}&chapterid={chapid}&sign={sign2}",proxies=tor,headers=header,timeout=20).text
        datachapter = json.loads(datachapter3)
    except:
        return {"status":"error","code":2,"data":"Không tải được web faloo"}
    if "本书没有授权" in datachapter:
        return {"status":"error","code":1,"data":"Truyện Không Hỗ Trợ"}
    if datachapter['data']['content'] != "null":
        datatext =  datachapter['data']['name']+"\r\n"+datachapter['data']['content'].replace("\u3000","")+"\r\n<$$$===$$$>\r\n"
        with open(f"{bookid}\{chapid}.txt", 'w', encoding='utf-8') as f5:
                f5.write(datatext)
                
        return datatext.replace("\r\n","<br><br>")
    else:
        return {"status":"error","code":3,"data":"Chương không có nội dung"}




@app.get('/getlink')
def get_link(link:HttpUrl):
	if "sangtacviet"  in link:
		linkinfo = re.search('truyen/(?P<host>.*?)/(?P<style>.*?)/(?P<bookid>.*)/(?P<cid>.*)/',link)

		if linkinfo is not None:
			data = getContentStv(linkinfo['host'],linkinfo['bookid'],linkinfo['cid'],linkinfo['style'])
			try:
				text = str(data['data']).replace('</i> <i','</i><i')
				rereplace = "<i h='.*?'t='|'v='.*?>.*?</i>"
				text = re.sub(rereplace,'',text)
				text = text.replace("<i t='",'').replace("'h='",'')
				
				return {'tenTruyen':data['bookname'],'tenChuong':data['chaptername'],'host':linkinfo['host'],'idTruyen':linkinfo['bookid'],'idChuong':linkinfo['cid'],'noiDung':text,
						'link':f"http://sangtacviet.com/truyen/{linkinfo['host']}/{linkinfo['style']}/{linkinfo['bookid']}/{linkinfo['cid']}/"}
			except:
				return {'error':'Chương truyện ko có nội dung'}
				
				
				
				

		
		linkinfo = re.search('truyen/(?P<host>.*?)/(?P<style>.*?)/(?P<bookid>.*)/',link)
		if linkinfo is not None:
			info = getInfoTruyenStv(linkinfo['host'],linkinfo['bookid'],linkinfo['style'])
			if info is None:
				return {'error':"Không lấy được thông tin truyện"}
			return info
		
		
		return {'error':"Cấu trúc link sáng tác việt không đúng"}
	bookinfo = get_info(link)
	if bookinfo is None:
		return None
	else:
		return bookinfo
    
@app.get('/gethost')
def get_link(link:HttpUrl):
	if "sangtacviet"  in link:
		linkinfo = re.search('truyen/(?P<host>.*?)/(?P<style>.*?)/(?P<bookid>.*)/(?P<cid>.*)/',link)
		if linkinfo is not None:
			return {'host':f"stv-{linkinfo['host']}",'bookid':linkinfo['bookid'],'chapid':linkinfo['cid']}

		linkinfo = re.search('truyen/(?P<host>.*?)/(?P<style>.*?)/(?P<bookid>.*)/',link)
		if linkinfo is not None:
			return {'host':f"stv-{linkinfo['host']}",'bookid':linkinfo['bookid'],'chapid':None}

	hostinfo = check_host(link)
	if hostinfo is None:
		return None
	else:
		return hostinfo
    
       
@app.post("/upload/")
async def up_load_mul(listtext =Form(...),orders:int = Form(...)):
    list_chuong=[]
    try:
        cattext = re.split("<\$\$\$===\$\$\$>",listtext)
        print(len(cattext))
        if len(cattext) <= 1 :
           return "error"
        for text in cattext:
            findtext = text.strip().split('\n')
            if len(findtext) <=1  :
                continue
            cattext3 = text.replace(findtext[0],'')
            cattext3 = cattext3.strip()
            print(findtext[0])
            cattext3 = cattext3.replace('\r\n\r\n\r\n\r\n',"<br><br>").replace('\r\n\r\n\r\n',"<br><br>").replace('\r\n\r\n',"<br><br>").replace('\r\n',"<br><br>")
            list_chuong.append({"title":findtext[0],"content":cattext3,"orders":orders})
            orders += 1
    except:
        list_chuong= "error"
    return list_chuong

@app.get("/upload/",response_class=HTMLResponse)
async def up_load_form(req:Request):
    return template.TemplateResponse("upload.html",{"request":req})
@app.get("/")
async def indexs():
    return {"error":1}
if __name__ == "__main__":
    uvicorn.run("a:app", host="0.0.0.0", port=8000, reload=True, server_header=False)
