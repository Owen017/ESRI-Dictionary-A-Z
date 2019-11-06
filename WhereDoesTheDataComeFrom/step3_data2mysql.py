#核心语句
#sql = '''INSERT INTO GISAZ(ID,WORDS,MEANING,LETTER)VALUES(%s,'%s',"%s",'%s');'''
#%(countId,pymysql.escape_string(defList[0][1]),pymysql.escape_string(defList[0][0]),alpha)
#cursor.execute()
#db.commit()
#db.close()
	

#0、核心语句调试了很久，最终使用#三层引号、#%s、#pymysql.escape_string()的方式集合完成了sql语句的撰写；
#pymysql.escape_string()会将数据值string中的 单引号等有意义的符号 通过 多层转义 的方式取消对语句的影响；
#能成功的两种相同效果的无%s语句:(不必深究，下次要用时再根据这次的经验尝试即可)
#（一）'''INSERT INTO GISAZ(ID,WORDS,MEANING,LETTER)VALUES(1,'3D feature',"A representation of a three-dimensional, 
#real-world object in a map or scene, with elevation values (z-values) stored within the feature\\'s geometry. Besides geometry, 
#3D features may have attributes stored in a feature table. In applications such as CAD, 3D features are often referred to 
#as 3D models.",'3');'''
#（二）'INSERT INTO GISAZ(ID,WORDS,MEANING,LETTER)VALUES(1,\'3D feature\',"A representation of a three-dimensional, 
#real-world object in a map or scene, with elevation values (z-values) stored within the feature\\\'s geometry. Besides geometry, 
#3D features may have attributes stored in a feature table. In applications such as CAD, 3D features are often referred to 
#as 3D models.",\'3\');'
#参考文章：https://blog.csdn.net/qq_36622490/article/details/87455903
#1、下面这句SQL删除了相同id的行数据，但是没能保留住其中id最小的那一条行数据;网上方法尝试数次未能成功，报sql语法错误:delete from cqssc where id in (select id from (select id from cqssc where expect in (select expect from cqssc group by expect having count(expect)>1) and id not in (select min(id) from cqssc group by expect having count(expect)>1)) as tmpresult)
#delete from gisaz where id in (select id from (select id from gisaz where ID in (select ID from gisaz group by ID having count(ID)>1)) as tmpresult)
#参考文章：https://www.cnblogs.com/jdbeyond/p/8157224.html
#2、已在Letter列将所有首字母小写
#3、初次插入数据我选择分3次进行，犯下了重置countId的错误；要注意countId是ID字段，不能重置进而引起ID重复。
#4、暂时在数据表中空置了一列other，打算用其放置图片，或是为"See xxxxxx"等meaning创造通往其他词条的超链接（数据库内大概有类似功能）。
#5、（无伤大雅，meaning的数据库类型设置了为text并且没有设置长度）在phpadmin中暂时没有找到完成显示meaning数据的方法，现在都是两条如"In ArcGIS, a message from a replica to its relativ..."和"原始长度207"的显示；
#6、（Warning!）部分Words的长度超过了预设的varchar(20)，造成部分词语名称不全。只得修改表结构，重新写入数据。varchar(40)也不够，应该去写一个找出defList[n][1]最大值的程序。
#未来再做数据库还是得先确定最大长度再建表；通过简单的程序'max=len(defList[0][1]);for i in range(0,1729):{if(len(defList[i][1])>max):{max=len(defList[i][1])}}'得到最大值为50，故设定varchar(60)。
#7、考虑是否要把后119条数据按照首字母重新洗入前面的数据中。


#数据库连接
from G2_dataClean import defList
import pymysql
db = pymysql.connect(host='localhost',user='smile',password='happyhappy',db='gisdictionary',charset='UTF8')
cursor = db.cursor()

#数据写入
countId = 1 #ID字段
for i in range(0,1729): #range(0,len(defList))
        alpha = defList[i][1][0:1].lower() #alpha即letter字段，为单词首字母。这一步截取出首字母并.lower()小写。
        if not(alpha.isalpha()):	#判断首字母是否为a-z字母，如果不是a-z，则分配'#'给它。
        	alpha = '#'
        sql = '''INSERT INTO GISAZ(ID,WORDS,MEANING,LETTER)VALUES(%s,'%s',"%s",'%s');'''%(countId,pymysql.escape_string(defList[i][1]),pymysql.escape_string(defList[i][0]),alpha)
        countId += 1
        cursor.execute(sql)
try:
        db.commit()
except Exception:
        print("发生错误",Exception) #不会捕获mysql的warning警告，Try...Except只捕获Error错误。
        db.rollback()	#回滚事务
finally:
        db.close()
