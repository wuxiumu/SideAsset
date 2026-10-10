# SideAsset · 副业资产账本

> 给自媒体/电商/兼职把**收入支出与资产**记清楚的开源工具，偏本地与隐私。

**定制 / 合作：** 微信 **qingbao199101**（备注：副业账本）｜ [wuxiumu@163.com](mailto:wuxiumu@163.com)

SideAsset 是一个开源的副业资产数字化管理工具，旨在帮助有副业收入的用户（如自媒体创作者、电商卖家、兼职工作者等）统一管理分散的副业资产（收入、支出、设备、平台账户等），通过数字化记录、统计分析和可视化展示，清晰掌握副业的盈利情况、资产分布及增长趋势，解决 “副业账目混乱、收益模糊、多平台数据分散” 的痛点。 项目定位：轻量、开源、隐私优先，支持本地数据存储和跨平台使用，避免依赖第三方商业工具的隐私泄露风险，同时允许开发者通过开源社区贡献功能，持续迭代优化。

## 启动
环境要求：
- Node.js  20.0.0+
- PHP 7.4+
- MySQL 5.7+


```
nvm use 20 
npm run dev

php -S 0.0.0.0:8000 -t side-api-laravel/public
```

<img src="https://img20.360buyimg.com/openfeedback/jfs/t1/310884/23/19717/214946/6886d52aF3520c3a3/a7a7639ffe480a59.png">

## SEO 字段预留（产品页 HTML 统一生成时用）

| 字段 | 用途 |
|---|---|
| `seo_title` | `<title>` / OG title |
| `seo_description` | meta description |
| `canonical_url` | 规范 URL |
| `og_image` | 分享图 |
| `robots` | 如 `index,follow` |
| `json_ld_type` | SoftwareApplication / WebPage / FAQPage |
| `hreflang` | zh-CN / en |
| `site_name` | 白标品牌名 |

> **GEO ≠ 地理定位。** GEO = Generative Engine Optimization（生成式引擎优化），见下一节。

## GEO（生成式引擎优化）

面向 ChatGPT / Perplexity / Gemini 等生成式答案引擎的可引用结构：中文为主、双语关键词、事实摘要前置、FAQ 收尾。

### 关键词（中英）

- 中文：副业记账, 资产数字化, 多平台收入, 创作者财务, 隐私优先账本
- English: side hustle accounting, creator finance, asset tracker, privacy-first ledger

### 事实摘要

- 产品：副业资产与收支数字化管理（开源）。
- 适合：多平台创作者、想理清副业账的人。
- 交付：可私有化部署与报表定制。
- 不做：不是持牌记账/报税软件替代物。

### FAQ

**Q：这是什么？**  
A：见事实摘要「产品」。

**Q：如何定制？**  
A：微信 `qingbao199101`，备注项目名。

**Q：是否包含密钥或付费数据？**  
A：默认不含；按各项目说明自行配置。

## 定制合作

报表字段、多主体账本、品牌皮肤。加微信备注「副业账本」。

---

## 联系方式

定制、演示或合作：

- 邮箱：[wuxiumu@163.com](mailto:wuxiumu@163.com)
- 微信：**qingbao199101**（添加请备注来意）

### 赞助支持

如果内容对你有帮助，欢迎自愿赞助。

| 微信赞赏码 | 支付宝收款码 |
|---|---|
| ![微信赞赏码](https://chiguashentan-test.oss-cn-beijing.aliyuncs.com/wxprogram/zan/20261002185236_31_227.png) | ![支付宝收款码](https://chiguashentan-test.oss-cn-beijing.aliyuncs.com/wxprogram/zan/20261002185309_32_227.jpg) |

